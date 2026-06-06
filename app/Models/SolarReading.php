<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SolarReading extends Model
{
    protected $table = 'solar_readings';

    public const DISPLAY_TIMEZONE = 'Asia/Jakarta';
    
    protected $fillable = [
        'device_id',
        'recorded_at',
        'voltage_v',
        'current_a',
        'soc_percent',
        'temperature_c',
        'relay_status'
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
        ];
    }

    public static function formatLocalTime($datetime): string
    {
        if (!$datetime) {
            return '-';
        }

        return Carbon::parse($datetime, 'UTC')
            ->timezone(self::DISPLAY_TIMEZONE)
            ->format('d/m/Y H:i:s');
    }

    public static function applyDateFilter($query, ?string $startDate, ?string $endDate)
    {
        if ($startDate && $endDate) {
            $query->whereBetween('recorded_at', [
                Carbon::parse($startDate, self::DISPLAY_TIMEZONE)->startOfDay()->utc(),
                Carbon::parse($endDate, self::DISPLAY_TIMEZONE)->endOfDay()->utc(),
            ]);
        }

        return $query;
    }

    public function toHistoryArray(): array
    {
        return [
            'waktu' => self::formatLocalTime($this->recorded_at),
            'tegangan' => $this->voltage_v,
            'arus' => $this->current_a,
            'soc' => $this->soc_percent,
            'suhu' => $this->temperature_c,
            'relay' => $this->relay_status,
        ];
    }

    public function toExportArray(): array
    {
        return [
            'waktu' => self::formatLocalTime($this->recorded_at),
            'voltage_v' => $this->voltage_v,
            'current_a' => $this->current_a,
            'soc_percent' => $this->soc_percent,
            'temperature_c' => $this->temperature_c,
            'relay_status' => $this->relay_status,
        ];
    }
    
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}