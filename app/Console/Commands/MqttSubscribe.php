<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\Device;
use App\Models\SolarReading;
use Carbon\Carbon;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe ke topik MQTT dari ESP32';

    public function handle()
    {
        $this->info('Menunggu data dari ESP32...');

        $server = '10.12.206.24';  // Ganti dengan IP laptop Anda
        $port = 1883;
        $clientId = 'Laravel_Subscriber';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings())
            ->setConnectTimeout(60)
            ->setKeepAliveInterval(60);

        try {
            $mqtt->connect($connectionSettings);
            $this->info('Terhubung ke MQTT broker!');
        } catch (\Exception $e) {
            $this->error('Gagal terhubung: ' . $e->getMessage());
            return 1;
        }

        $mqtt->subscribe('surya/panel1/data', function ($topic, $message) {
            $this->info('Data diterima: ' . $message);
            
            $data = json_decode($message, true);
            
            // Cari atau buat device (ESP32)
            $device = Device::firstOrCreate(
                ['code' => 'ESP32_SOLAR_01'],
                [
                    'name' => 'Panel Surya Polibatam',
                    'location' => 'Lab Teknik Informatika',
                    'is_active' => true,
                ]
            );
            
            // Update last_seen_at
            $device->update(['last_seen_at' => Carbon::now('UTC')]);
            
            // Simpan data ke solar_readings
            SolarReading::create([
                'device_id' => $device->id,
                'recorded_at' => Carbon::now('UTC'),
                'voltage_v' => $data['tegangan'] ?? 0,
                'current_a' => $data['arus'] ?? 0,
                'soc_percent' => $data['soc'] ?? 0,
                'temperature_c' => 0, // Belum ada sensor suhu
                'relay_status' => $data['status'] === 'CHARGING' ? 'ON' : 'OFF',
            ]);
            
            $this->info('✅ Data tersimpan ke database');
            
        }, 0);

        while (true) {
            $mqtt->loopOnce(0, true);
            usleep(10000);
        }

        $mqtt->disconnect();
    }
}