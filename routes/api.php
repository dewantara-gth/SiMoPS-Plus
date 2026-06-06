<?php

use App\Models\SolarReading;
use App\Models\RelayEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ambil data terbaru (untuk card dashboard)
Route::get('/latest', function () {
    $latest = SolarReading::latest('recorded_at')->first();
    
    if (!$latest) {
        return response()->json([
            'tegangan' => 0,
            'arus' => 0,
            'soc' => 0,
            'relay_status' => 'OFF',
            'timestamp' => Carbon::now('UTC')->toIso8601String(),
        ]);
    }
    
    return response()->json([
        'tegangan' => (float) $latest->voltage_v,
        'arus' => (float) $latest->current_a,
        'soc' => (int) $latest->soc_percent,
        'relay_status' => $latest->relay_status ?? 'OFF',
        'timestamp' => Carbon::parse($latest->recorded_at, 'UTC')->toIso8601String(),
    ]);
});

// Ambil data history untuk grafik
Route::get('/history-chart', function () {
    $hours = request('hours', 24);
    
    $data = SolarReading::where('recorded_at', '>=', Carbon::now('UTC')->subHours($hours))
        ->orderBy('recorded_at', 'asc')
        ->get([
            'recorded_at',
            'voltage_v as tegangan',
            'current_a as arus',
            'soc_percent as soc',
        ])
        ->map(fn ($row) => [
            'waktu' => SolarReading::formatLocalTime($row->recorded_at),
            'tegangan' => (float) $row->tegangan,
            'arus' => (float) $row->arus,
            'soc' => (int) $row->soc,
        ]);
    
    return response()->json($data);
});

// Ambil data history dengan pagination (terlama di atas)
Route::get('/history-table', function (Request $request) {
    $perPage = $request->get('per_page', 10);
    
    $query = SolarReading::query()
        ->orderBy('recorded_at', 'asc');

    SolarReading::applyDateFilter(
        $query,
        $request->get('start_date'),
        $request->get('end_date')
    );
    
    $paginated = $query->paginate($perPage);
    $paginated->getCollection()->transform(fn ($item) => $item->toHistoryArray());
    
    return response()->json($paginated);
});

// Kontrol relay (perintah MQTT)
Route::post('/control-relay', function (Request $request) {
    $status = $request->input('status');
    $mode = $request->input('mode', 'manual');
    
    try {
        $mqtt = new \PhpMqtt\Client\MqttClient('10.12.206.24', 1883, 'laravel_publisher');
        $settings = (new \PhpMqtt\Client\ConnectionSettings())->setKeepAliveInterval(60);
        $mqtt->connect($settings);
        $mqtt->publish('kontrol/panel1/relay', $status);
        $mqtt->disconnect();
    } catch (\Exception $e) {
        \Log::error('MQTT Publish failed: ' . $e->getMessage());
    }
    
    if (auth()->check()) {
        RelayEvent::create([
            'device_id' => 1,
            'mode' => $mode,
            'relay_state' => $status,
            'changed_by_user_id' => auth()->id(),
            'reason' => $mode === 'manual' ? 'Manual from dashboard' : 'Auto triggered',
            'changed_at' => Carbon::now('UTC'),
        ]);
    }
    
    return response()->json(['success' => true, 'status' => $status]);
})->middleware('auth');

// DELETE all history
Route::delete('/history-delete-all', function () {
    SolarReading::truncate();
    return response()->json(['success' => true]);
})->middleware('auth');

// Export data (terlama ke terbaru, waktu format WIB)
Route::get('/export-excel', function (Request $request) {
    $query = SolarReading::query()->orderBy('recorded_at', 'asc');

    SolarReading::applyDateFilter(
        $query,
        $request->get('start_date'),
        $request->get('end_date')
    );
    
    $data = $query->get()->map(fn ($item) => $item->toExportArray());
    
    return response()->json(['data' => $data, 'count' => $data->count()]);
})->middleware('auth');

// Export PDF memakai data yang sama
Route::get('/export-pdf', function (Request $request) {
    $query = SolarReading::query()->orderBy('recorded_at', 'asc');

    SolarReading::applyDateFilter(
        $query,
        $request->get('start_date'),
        $request->get('end_date')
    );
    
    $data = $query->get()->map(fn ($item) => $item->toExportArray());
    
    return response()->json(['data' => $data, 'count' => $data->count()]);
})->middleware('auth');
