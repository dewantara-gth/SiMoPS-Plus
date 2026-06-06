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
                    <p class="text-2xl font-bold" id="tegangan">-- V</p>
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
                    <p class="text-2xl font-bold" id="arus">-- A</p>
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
                    <p class="text-2xl font-bold" id="soc">--%</p>
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
    let chartDataHistory = {
        tegangan: [],
        arus: [],
        soc: [],
        labels: []
    };
    
    // CSRF Token untuk AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    function updateRelayDisplay(state) {
        const relayStatus = document.querySelector('.badge-relay');
        if (!relayStatus) return;
        if (state === 'ON') {
            relayStatus.className = 'badge-relay bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm';
            relayStatus.textContent = 'ON';
        } else {
            relayStatus.className = 'badge-relay bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm';
            relayStatus.textContent = 'OFF';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        setupRelayControl();
        loadDashboardData();
        startRealtimeUpdates();
        setupChartRangeSelectors();
    });
    
    async function loadDashboardData() {
        const data = await fetchLatestData();
        if (data) {
            updateSensorData(data);
            checkAlerts(data);
            resetTimeStamps();
        }
        await fetchInitialChartData();
    }
    
    // ==================== FUNGSI API (REAL DATA) ====================
    
    async function fetchLatestData() {
        try {
            const response = await fetch('/api/latest');
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Gagal fetch data real:', error);
            return null;
        }
    }
    
    async function fetchChartData(hours = 24) {
        try {
            const response = await fetch(`/api/history-chart?hours=${hours}`);
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Gagal fetch chart data:', error);
            return [];
        }
    }
    
    async function sendRelayCommand(status, mode = 'manual') {
        try {
            const response = await fetch('/api/control-relay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: status, mode: mode })
            });
            const result = await response.json();
            return result.success;
        } catch (error) {
            console.error('Gagal kirim perintah relay:', error);
            return false;
        }
    }
    
    async function fetchDeviceStatus() {
        try {
            const data = await fetchLatestData();
            if (data && data.timestamp) {
                const lastSeen = new Date(data.timestamp);
                const now = new Date();
                const diffSeconds = (now - lastSeen) / 1000;
                const isOnline = diffSeconds < 30;
                updateDeviceStatus(isOnline);
            } else {
                updateDeviceStatus(false);
            }
        } catch (error) {
            updateDeviceStatus(false);
        }
    }
    
    // ==================== INISIALISASI CHART ====================
    
    function initCharts() {
        const ctxTegangan = document.getElementById('chartTegangan').getContext('2d');
        const ctxArus = document.getElementById('chartArus').getContext('2d');
        const ctxSOC = document.getElementById('chartSOC').getContext('2d');
        const chartTextColor = '#cbd5e1';
        const chartGridColor = 'rgba(148, 163, 184, 0.2)';
        
        chartTegangan = new Chart(ctxTegangan, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Tegangan (V)', data: [], borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.1)', tension: 0.4, fill: true }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: chartTextColor } } }, scales: { x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }, y: { beginAtZero: true, max: 20, ticks: { color: chartTextColor }, grid: { color: chartGridColor }, title: { display: true, text: 'Tegangan (V)', color: chartTextColor } } } }
        });
        
        chartArus = new Chart(ctxArus, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Arus (A)', data: [], borderColor: 'rgb(34, 197, 94)', backgroundColor: 'rgba(34, 197, 94, 0.1)', tension: 0.4, fill: true }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: chartTextColor } } }, scales: { x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }, y: { beginAtZero: true, max: 5, ticks: { color: chartTextColor }, grid: { color: chartGridColor }, title: { display: true, text: 'Arus (A)', color: chartTextColor } } } }
        });
        
        chartSOC = new Chart(ctxSOC, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'SOC (%)', data: [], borderColor: 'rgb(249, 115, 22)', backgroundColor: 'rgba(249, 115, 22, 0.1)', tension: 0.4, fill: true }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: chartTextColor } } }, scales: { x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }, y: { beginAtZero: true, max: 100, ticks: { color: chartTextColor }, grid: { color: chartGridColor }, title: { display: true, text: 'SOC (%)', color: chartTextColor } } } }
        });
    }
    
    function applyChartData(data) {
        if (!data.length) return;
        
        chartTegangan.data.labels = data.map(item => item.waktu);
        chartTegangan.data.datasets[0].data = data.map(item => item.tegangan);
        chartTegangan.update();
        
        chartArus.data.labels = data.map(item => item.waktu);
        chartArus.data.datasets[0].data = data.map(item => item.arus);
        chartArus.update();
        
        chartSOC.data.labels = data.map(item => item.waktu);
        chartSOC.data.datasets[0].data = data.map(item => item.soc);
        chartSOC.update();
        
        chartDataHistory = {
            labels: data.map(item => item.waktu),
            tegangan: data.map(item => item.tegangan),
            arus: data.map(item => item.arus),
            soc: data.map(item => item.soc)
        };
    }
    
    async function fetchInitialChartData() {
        const hours = parseInt(document.getElementById('range-tegangan').value) || 24;
        const data = await fetchChartData(hours);
        applyChartData(data);
    }
    
    function setupChartRangeSelectors() {
        const selectors = [
            { id: 'range-tegangan', chart: () => chartTegangan, field: 'tegangan' },
            { id: 'range-arus', chart: () => chartArus, field: 'arus' },
            { id: 'range-soc', chart: () => chartSOC, field: 'soc' },
        ];
        
        selectors.forEach(({ id, chart, field }) => {
            document.getElementById(id).addEventListener('change', async function() {
                const hours = parseInt(this.value);
                const data = await fetchChartData(hours);
                if (!data.length) return;
                
                const targetChart = chart();
                targetChart.data.labels = data.map(item => item.waktu);
                targetChart.data.datasets[0].data = data.map(item => item[field]);
                targetChart.update();
            });
        });
    }
    
    // ==================== UPDATE REAL-TIME ====================
    
    function startRealtimeUpdates() {
        // Update sensor setiap 5 detik dari database
        setInterval(async () => {
            const data = await fetchLatestData();
            if (data) {
                updateSensorData(data);
                updateChartsFromDatabase();
                checkAlerts(data);
                resetTimeStamps();
            }
        }, 5000);
        
        // Update timestamp setiap detik
        setInterval(() => { updateTimeStamps(); }, 1000);
        
        // Update device status setiap 10 detik
        setInterval(() => { fetchDeviceStatus(); }, 10000);
        
        // Panggil sekali di awal
        fetchDeviceStatus();
    }
    
    function updateSensorData(data) {
        if (data.tegangan !== undefined && data.tegangan !== null) {
            document.getElementById('tegangan').textContent = data.tegangan + ' V';
        }
        if (data.arus !== undefined && data.arus !== null) {
            document.getElementById('arus').textContent = data.arus + ' A';
        }
        if (data.soc !== undefined && data.soc !== null) {
            document.getElementById('soc').textContent = data.soc + '%';
        }
        if (data.relay_status) {
            const newRelayState = data.relay_status;
            relayState = newRelayState;
            updateRelayDisplay(relayState);
        }
    }
    
    async function updateChartsFromDatabase() {
        const hours = parseInt(document.getElementById('range-tegangan').value) || 24;
        const data = await fetchChartData(hours);
        applyChartData(data);
    }
    
    function updateTimeStamps() {
        const times = ['tegangan-time', 'arus-time', 'soc-time', 'relay-time'];
        times.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                const currentText = element.textContent;
                const seconds = parseInt(currentText) || 0;
                if (seconds < 60) element.textContent = (seconds + 1) + 's';
            }
        });
    }
    
    function resetTimeStamps() {
        document.getElementById('tegangan-time').textContent = '0s';
        document.getElementById('arus-time').textContent = '0s';
        document.getElementById('soc-time').textContent = '0s';
        document.getElementById('relay-time').textContent = '0s';
    }
    
    function updateDeviceStatus(isOnline) {
        const statusElement = document.getElementById('device-status');
        if (!statusElement) return;
        if (isOnline) {
            statusElement.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
            statusElement.innerHTML = '<i class="fas fa-circle text-xs mr-1"></i> Online';
        } else {
            statusElement.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
            statusElement.innerHTML = '<i class="fas fa-circle text-xs mr-1"></i> Offline';
            showToast('Device Offline', 'Tidak ada data dari device dalam 30 detik terakhir', 'error');
        }
    }
    
    // ==================== ALERT & NOTIFIKASI ====================
    
    function checkAlerts(data) {
        const tegangan = parseFloat(data.tegangan);
        const soc = parseFloat(data.soc);
        
        if (soc >= 100) {
            const title = '⚠️ OVERCHARGE ALERT - BATERAI PENUH';
            const message = `Baterai telah mencapai 100% (SOC: ${soc}%). Relay akan dimatikan untuk menghentikan pengisian.`;
            showToast(title, message, 'warning');
            addNotification(title, message, 'warning');
            
            if (currentMode === 'auto') {
                relayState = 'OFF';
                updateRelayDisplay(relayState);
                document.getElementById('relay-time').textContent = '0s';
                sendRelayCommand('OFF', 'auto');
                showToast('Relay Otomatis', 'Relay dimatikan untuk mencegah overcharge', 'info');
            } else {
                showToast('Peringatan', 'Baterai penuh! Segera matikan relay manual', 'warning');
            }
        } else if (tegangan < 11.5) {
            const title = '⚠️ LOW BATTERY ALERT';
            const message = `Tegangan rendah: ${tegangan}V. Segera lakukan pengisian.`;
            showToast(title, message, 'error');
            addNotification(title, message, 'error');
        }
    }
    
    function addNotification(title, message, type) {
        let notifications = JSON.parse(localStorage.getItem('solar_notifications') || '[]');
        notifications.unshift({ title, message, type, time: new Date().toISOString() });
        notifications = notifications.slice(0, 50);
        localStorage.setItem('solar_notifications', JSON.stringify(notifications));
    }
    
    // ==================== KONTROL RELAY ====================
    
    function setupRelayControl() {
        const toggleBtn = document.getElementById('toggle-relay');
        const modeManual = document.getElementById('mode-manual');
        const modeAuto = document.getElementById('mode-auto');
        const relayMode = document.getElementById('relay-mode');
        const manualControls = document.getElementById('manual-controls');
        
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
        
        updateRelayDisplay(relayState);
        updateModeUI();
        
        modeManual.addEventListener('click', function() {
            currentMode = 'manual';
            updateModeUI();
            if (autoInterval) { clearInterval(autoInterval); autoInterval = null; }
            sendRelayCommand(relayState, 'manual');
            showToast('Mode Manual', 'Relay dapat dikontrol manual', 'info');
        });
        
        modeAuto.addEventListener('click', function() {
            currentMode = 'auto';
            updateModeUI();
            startAutoMode();
            showToast('Mode Auto', 'Relay akan otomatis berdasarkan kondisi baterai', 'info');
        });
        
        toggleBtn.addEventListener('click', async function() {
            if (currentMode === 'manual') {
                const newState = relayState === 'ON' ? 'OFF' : 'ON';
                const success = await sendRelayCommand(newState, 'manual');
                if (success) {
                    relayState = newState;
                    updateRelayDisplay(relayState);
                    document.getElementById('relay-time').textContent = '0s';
                    showToast('Relay Manual', `Relay diubah ke mode ${relayState}`, 'success');
                } else {
                    showToast('Gagal', 'Gagal mengirim perintah ke relay', 'error');
                }
            } else {
                showToast('Mode Auto', 'Ganti ke mode manual untuk mengontrol relay', 'warning');
            }
        });
        
        function startAutoMode() {
            if (autoInterval) clearInterval(autoInterval);
            autoInterval = setInterval(async () => {
                if (currentMode === 'auto') {
                    const data = await fetchLatestData();
                    if (data) {
                        const tegangan = parseFloat(data.tegangan);
                        const soc = parseFloat(data.soc);
                        let newState = relayState;
                        
                        if (soc >= 100) newState = 'OFF';
                        else if (tegangan < 11.5) newState = 'ON';
                        else if (soc >= 50 && tegangan > 12) newState = 'ON';
                        else newState = 'OFF';
                        
                        if (newState !== relayState) {
                            const success = await sendRelayCommand(newState, 'auto');
                            if (success) {
                                relayState = newState;
                                updateRelayDisplay(relayState);
                                document.getElementById('relay-time').textContent = '0s';
                                console.log(`Relay auto berubah ke: ${relayState}`);
                            }
                        }
                    }
                }
            }, 5000);
        }
    }
    
    // ==================== TOAST NOTIFICATION ====================
    
    function showToast(title, message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-50 border-green-400' : type === 'warning' ? 'bg-yellow-50 border-yellow-400' : type === 'error' ? 'bg-red-50 border-red-400' : 'bg-blue-50 border-blue-400';
        const iconColor = type === 'success' ? 'text-green-400' : type === 'warning' ? 'text-yellow-400' : type === 'error' ? 'text-red-400' : 'text-blue-400';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
        toast.className = `max-w-sm w-full ${bgColor} border-l-4 p-4 mb-2 rounded shadow-lg flex justify-between items-start animate-slideIn`;
        toast.innerHTML = `<div class="flex"><div class="flex-shrink-0"><i class="fas ${icon} ${iconColor} text-lg"></i></div><div class="ml-3"><p class="text-sm font-medium text-gray-900">${title}</p><p class="text-sm text-gray-700">${message}</p></div></div><button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>`;
        container.appendChild(toast);
        setTimeout(() => { if (toast.parentElement) toast.remove(); }, 5000);
    }
    
    window.sendRelayCommand = sendRelayCommand;
</script>
@endpush
