@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Cards Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tegangan Baterai -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tegangan Baterai</p>
                    <p class="text-2xl font-bold" id="tegangan">13.2 V</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-bolt text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-gray-500">Update: <span id="tegangan-time">0s</span></span>
            </div>
        </div>
        
        <!-- Arus Charge -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Arus Charge</p>
                    <p class="text-2xl font-bold" id="arus">2.5 A</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-charging-station text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-gray-500">Update: <span id="arus-time">0s</span></span>
            </div>
        </div>
        
        <!-- SOC -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">State of Charge</p>
                    <p class="text-2xl font-bold" id="soc">78%</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-battery-three-quarters text-orange-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-gray-500">Update: <span id="soc-time">0s</span></span>
            </div>
        </div>
        
        <!-- Status Relay dengan Mode Switch -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Status Relay</p>
                    <div class="flex items-center mt-1">
                        <span class="text-2xl font-bold mr-3" id="relay-status">
                            <span class="badge-relay bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">ON</span>
                        </span>
                        
                        <!-- Mode Selector -->
                        <div class="flex items-center ml-2 space-x-1">
                            <button id="mode-manual" class="px-3 py-1 text-xs rounded-l-lg bg-blue-600 text-white font-medium">Manual</button>
                            <button id="mode-auto" class="px-3 py-1 text-xs rounded-r-lg bg-gray-200 text-gray-700 font-medium">Auto</button>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Tombol Toggle Manual (hanya muncul di mode manual) -->
            <div class="mt-3" id="manual-controls">
                <button id="toggle-relay" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-power-off mr-2"></i>
                    Toggle Relay (Manual)
                </button>
            </div>
            
            <div class="mt-2 flex justify-between items-center">
                <span class="text-xs text-gray-500">Update: <span id="relay-time">0s</span></span>
                <span class="text-xs text-gray-500">Mode: <span id="relay-mode" class="font-semibold text-blue-600">Manual</span></span>
            </div>
        </div>
    </div>
    
    <!-- Grafik -->
    <div class="space-y-4">
        <!-- Grafik Tegangan -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Grafik Tegangan (V)</h3>
                <select id="range-tegangan" class="border rounded px-3 py-1 text-sm">
                    <option value="1">1 Jam</option>
                    <option value="6">6 Jam</option>
                    <option value="24" selected>24 Jam</option>
                    <option value="168">7 Hari</option>
                </select>
            </div>
            <div class="w-full h-64">
                <canvas id="chartTegangan"></canvas>
            </div>
        </div>
        
        <!-- Grafik Arus -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Grafik Arus (A)</h3>
                <select id="range-arus" class="border rounded px-3 py-1 text-sm">
                    <option value="1">1 Jam</option>
                    <option value="6">6 Jam</option>
                    <option value="24" selected>24 Jam</option>
                    <option value="168">7 Hari</option>
                </select>
            </div>
            <div class="w-full h-64 overflow-x-auto">
                <div class="min-w-[600px] h-64">
                    <canvas id="chartArus"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Grafik SOC -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Grafik SOC (%)</h3>
                <select id="range-soc" class="border rounded px-3 py-1 text-sm">
                    <option value="1">1 Jam</option>
                    <option value="6">6 Jam</option>
                    <option value="24" selected>24 Jam</option>
                    <option value="168">7 Hari</option>
                </select>
            </div>
            <div class="w-full h-64 overflow-x-auto">
                <div class="min-w-[600px] h-64">
                    <canvas id="chartSOC"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi chart
    let chartTegangan, chartArus, chartSOC;
    
    // Variabel global untuk relay
    let relayState = 'ON';
    let currentMode = 'manual'; // 'manual' atau 'auto'
    let autoInterval = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        startRealtimeUpdates();
        startAlertSimulation();
        setupRelayControl();
    });
    
    function initCharts() {
        const ctxTegangan = document.getElementById('chartTegangan').getContext('2d');
        const ctxArus = document.getElementById('chartArus').getContext('2d');
        const ctxSOC = document.getElementById('chartSOC').getContext('2d');
        
        // Chart Tegangan
        chartTegangan = new Chart(ctxTegangan, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tegangan (V)',
                    data: [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        title: {
                            display: true,
                            text: 'Tegangan (V)'
                        }
                    }
                }
            }
        });
        
        // Chart Arus
        chartArus = new Chart(ctxArus, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Arus (A)',
                    data: [],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        title: {
                            display: true,
                            text: 'Arus (A)'
                        }
                    }
                }
            }
        });
        
        // Chart SOC
        chartSOC = new Chart(ctxSOC, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'SOC (%)',
                    data: [],
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'SOC (%)'
                        }
                    }
                }
            }
        });
    }
    
    function startRealtimeUpdates() {
        // Update sensor setiap 5 detik (relay tidak ikut update)
        setInterval(() => {
            const data = SolarData.getCurrentData();
            updateSensorData(data);
            updateCharts(data);
            checkAlerts(data);
        }, 5000);
        
        // Update waktu setiap detik
        setInterval(() => {
            updateTimeStamps();
        }, 1000);
        
        // Simulasi status device (toggle setiap 15 detik)
        setInterval(() => {
            const isOnline = Math.random() > 0.3; // 70% chance online
            updateDeviceStatus(isOnline);
        }, 15000);
    }
    
    function updateSensorData(data) {
        document.getElementById('tegangan').textContent = data.tegangan + ' V';
        document.getElementById('arus').textContent = data.arus + ' A';
        document.getElementById('soc').textContent = data.soc + '%';
        
        // Relay TIDAK diupdate otomatis dari data dummy
        // Relay hanya berubah melalui tombol manual atau mode auto
    }
    
    function updateTimeStamps() {
        // Update timestamp untuk setiap card
        const times = ['tegangan-time', 'arus-time', 'soc-time', 'relay-time'];
        times.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                const currentText = element.textContent;
                const seconds = parseInt(currentText) || 0;
                if (seconds < 60) {
                    element.textContent = (seconds + 1) + 's';
                }
            }
        });
    }
    
    function resetTimeStamps() {
        document.getElementById('tegangan-time').textContent = '0s';
        document.getElementById('arus-time').textContent = '0s';
        document.getElementById('soc-time').textContent = '0s';
        document.getElementById('relay-time').textContent = '0s';
    }
    
    function updateCharts(data) {
        const time = new Date().toLocaleTimeString();
        
        // Update chart tegangan
        if (chartTegangan.data.labels.length > 20) {
            chartTegangan.data.labels.shift();
            chartTegangan.data.datasets[0].data.shift();
        }
        chartTegangan.data.labels.push(time);
        chartTegangan.data.datasets[0].data.push(parseFloat(data.tegangan));
        chartTegangan.update();
        
        // Update chart arus
        if (chartArus.data.labels.length > 20) {
            chartArus.data.labels.shift();
            chartArus.data.datasets[0].data.shift();
        }
        chartArus.data.labels.push(time);
        chartArus.data.datasets[0].data.push(parseFloat(data.arus));
        chartArus.update();
        
        // Update chart SOC
        if (chartSOC.data.labels.length > 20) {
            chartSOC.data.labels.shift();
            chartSOC.data.datasets[0].data.shift();
        }
        chartSOC.data.labels.push(time);
        chartSOC.data.datasets[0].data.push(parseFloat(data.soc));
        chartSOC.update();
    }
    
    function updateDeviceStatus(isOnline) {
        const statusElement = document.getElementById('device-status');
        if (isOnline) {
            statusElement.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
            statusElement.innerHTML = '<i class="fas fa-circle text-xs mr-1"></i> Online';
        } else {
            statusElement.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
            statusElement.innerHTML = '<i class="fas fa-circle text-xs mr-1"></i> Offline';
            
            // Tampilkan alert device offline
            showToast('Device Offline', 'Tidak ada data dari device dalam 30 detik terakhir', 'error');
        }
    }
    
    function checkAlerts(data) {
    const tegangan = parseFloat(data.tegangan);
    const soc = parseFloat(data.soc);
    
    // Cek kondisi overcharge (baterai penuh)
    if (soc >= 100) {
        const title = '⚠️ OVERCHARGE ALERT - BATERAI PENUH';
        const message = `Baterai telah mencapai 100% (SOC: ${soc}%). Relay akan dimatikan untuk menghentikan pengisian.`;
        showToast(title, message, 'warning');
        
        // Tambah notifikasi ke history
        addNotification(title, message, 'warning');
        
        // OTOMATIS MATIKAN RELAY jika dalam mode auto
        if (currentMode === 'auto') {
            relayState = 'OFF';
            updateRelayDisplay(relayState);
            document.getElementById('relay-time').textContent = '0s';
            
            // Tampilkan notifikasi tambahan
            showToast('Relay Otomatis', 'Relay dimatikan untuk mencegah overcharge', 'info');
        } else {
            // Jika mode manual, tetap kasih peringatan
            showToast('Peringatan', 'Baterai penuh! Segera matikan relay manual', 'warning');
        }
    }
    // Cek kondisi low battery
    else if (tegangan < 11.5) {
        const title = '⚠️ LOW BATTERY ALERT';
        const message = `Tegangan rendah: ${tegangan}V. Segera lakukan pengisian.`;
        showToast(title, message, 'error');
        addNotification(title, message, 'error');
    }
}

function setupRelayControl() {
    const toggleBtn = document.getElementById('toggle-relay');
    const modeManual = document.getElementById('mode-manual');
    const modeAuto = document.getElementById('mode-auto');
    const relayStatus = document.querySelector('.badge-relay');
    const relayMode = document.getElementById('relay-mode');
    const manualControls = document.getElementById('manual-controls');
    
    // Update tampilan awal
    updateRelayDisplay(relayState);
    updateModeUI();
    
    // Event listener untuk tombol mode
    modeManual.addEventListener('click', function() {
        currentMode = 'manual';
        updateModeUI();
        
        // Hentikan interval auto jika ada
        if (autoInterval) {
            clearInterval(autoInterval);
            autoInterval = null;
        }
        
        showToast('Mode Manual', 'Relay dapat dikontrol manual', 'info');
    });
    
    modeAuto.addEventListener('click', function() {
        currentMode = 'auto';
        updateModeUI();
        
        // Mulai mode auto
        startAutoMode();
        
        showToast('Mode Auto', 'Relay akan otomatis berdasarkan kondisi baterai', 'info');
    });
    
    // Event listener untuk tombol toggle (hanya aktif di mode manual)
    toggleBtn.addEventListener('click', function() {
        if (currentMode === 'manual') {
            // Toggle state relay
            relayState = relayState === 'ON' ? 'OFF' : 'ON';
            updateRelayDisplay(relayState);
            
            // Reset timestamp relay
            document.getElementById('relay-time').textContent = '0s';
            
            showToast('Relay Manual', `Relay diubah ke mode ${relayState}`, 'success');
            
            // Log ke console (untuk debugging)
            console.log(`Relay diubah manual ke: ${relayState} pada ${new Date().toLocaleString()}`);
        } else {
            showToast('Mode Auto', 'Ganti ke mode manual untuk mengontrol relay', 'warning');
        }
    });
    
    function updateModeUI() {
        if (currentMode === 'manual') {
            modeManual.className = 'px-3 py-1 text-xs rounded-l-lg bg-blue-600 text-white font-medium';
            modeAuto.className = 'px-3 py-1 text-xs rounded-r-lg bg-gray-200 text-gray-700 font-medium';
            manualControls.style.display = 'block';
            relayMode.textContent = 'Manual';
            relayMode.className = 'font-semibold text-blue-600';
        } else {
            modeManual.className = 'px-3 py-1 text-xs rounded-l-lg bg-gray-200 text-gray-700 font-medium';
            modeAuto.className = 'px-3 py-1 text-xs rounded-r-lg bg-blue-600 text-white font-medium';
            manualControls.style.display = 'none';
            relayMode.textContent = 'Auto';
            relayMode.className = 'font-semibold text-green-600';
        }
    }
    
    function updateRelayDisplay(state) {
        if (state === 'ON') {
            relayStatus.className = 'badge-relay bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm';
            relayStatus.textContent = 'ON';
        } else {
            relayStatus.className = 'badge-relay bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm';
            relayStatus.textContent = 'OFF';
        }
    }
    
    function startAutoMode() {
        // Hentikan interval sebelumnya jika ada
        if (autoInterval) {
            clearInterval(autoInterval);
        }
        
        // Interval untuk mode auto (cek setiap 5 detik)
        autoInterval = setInterval(() => {
            if (currentMode === 'auto') {
                // Ambil data sensor terbaru dari DOM
                const teganganText = document.getElementById('tegangan').textContent;
                const socText = document.getElementById('soc').textContent;
                
                const tegangan = parseFloat(teganganText);
                const soc = parseFloat(socText);
                
                // LOGIKA AUTO RELAY YANG BARU:
                // 1. Jika SOC >= 100% (baterai penuh) -> RELAY OFF (berhenti charge)
                // 2. Jika tegangan < 11.5V (low battery) -> RELAY ON (mulai charge)
                // 3. Jika SOC antara 50-99% dan tegangan normal -> RELAY ON
                // 4. Kondisi lainnya -> RELAY OFF
                
                let newState;
                
                if (soc >= 100) {
                    // Baterai penuh, harus OFF
                    newState = 'OFF';
                    if (relayState !== 'OFF') {
                        showToast('Auto Cut-Off', 'Baterai penuh, relay dimatikan otomatis', 'warning');
                    }
                }
                else if (tegangan < 11.5) {
                    // Low battery, harus ON (charge)
                    newState = 'ON';
                    if (relayState !== 'ON') {
                        showToast('Auto Charge', 'Tegangan rendah, relay dinyalakan otomatis', 'info');
                    }
                }
                else if (soc >= 50 && tegangan > 12) {
                    // Kondisi normal, bisa charge
                    newState = 'ON';
                }
                else {
                    // Kondisi lainnya
                    newState = 'OFF';
                }
                
                // Jika state berubah, update relay
                if (newState !== relayState) {
                    relayState = newState;
                    updateRelayDisplay(relayState);
                    
                    // Reset timestamp relay
                    document.getElementById('relay-time').textContent = '0s';
                    
                    console.log(`Relay auto berubah ke: ${relayState} pada ${new Date().toLocaleString()}`);
                }
            }
        }, 5000); // Cek setiap 5 detik
    }
}
    
    
    function showToast(title, message, type = 'info') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-50 border-green-400' : 
                       type === 'warning' ? 'bg-yellow-50 border-yellow-400' : 
                       type === 'error' ? 'bg-red-50 border-red-400' : 
                       'bg-blue-50 border-blue-400';
        
        const iconColor = type === 'success' ? 'text-green-400' :
                         type === 'warning' ? 'text-yellow-400' :
                         type === 'error' ? 'text-red-400' :
                         'text-blue-400';
        
        const icon = type === 'success' ? 'fa-check-circle' :
                    type === 'warning' ? 'fa-exclamation-triangle' :
                    type === 'error' ? 'fa-times-circle' :
                    'fa-info-circle';
        
        toast.className = `max-w-sm w-full ${bgColor} border-l-4 p-4 mb-2 rounded shadow-lg flex justify-between items-start animate-slideIn`;
        toast.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas ${icon} ${iconColor} text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">${title}</p>
                    <p class="text-sm text-gray-700">${message}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }
    
    function startAlertSimulation() {
        setInterval(() => {
            if (Math.random() > 0.7) { // 30% chance muncul alert
                const alertTypes = [
                    { title: 'Overcharge Alert', message: 'Tegangan melebihi 14.4V', type: 'warning' },
                    { title: 'Low Battery Alert', message: 'Tegangan dibawah 11.5V', type: 'error' },
                    { title: 'Device Offline', message: 'Koneksi device terputus', type: 'error' }
                ];
                const randomAlert = alertTypes[Math.floor(Math.random() * alertTypes.length)];
                showToast(randomAlert.title, randomAlert.message, randomAlert.type);
            }
        }, 30000);
    }

    
</script>
@endpush