// Solar Data Dummy Generator
const SolarData = {
    // Generate data real-time (tanpa relay)
    getCurrentData() {
        // Simulasi data dengan kecenderungan naik ke 100% untuk testing
        const random = Math.random();
        let soc;
        
        // Untuk testing, kadang buat SOC tinggi
        if (random > 0.8) {
            soc = 100; // Overcharge scenario
        } else if (random > 0.6) {
            soc = 95 + Math.floor(Math.random() * 5); // 95-99%
        } else {
            soc = Math.floor(50 + Math.random() * 40); // 50-90%
        }
        
        return {
            tegangan: soc >= 100 ? (14.5 + Math.random() * 0.5).toFixed(1) : (12 + Math.random() * 3).toFixed(1),
            arus: soc >= 100 ? (0.1).toFixed(1) : (1 + Math.random() * 3).toFixed(1), // Arus kecil saat penuh
            soc: soc,
            suhu: soc >= 100 ? (30 + Math.random() * 5).toFixed(1) : (25 + Math.random() * 10).toFixed(1),
            timestamp: new Date().toISOString()
        };
    },
    
    // Generate historical data dengan ID unik
    generateHistoricalData(count = 100) {
        const data = [];
        const now = new Date();
        
        for (let i = 0; i < count; i++) {
            const date = new Date(now);
            date.setMinutes(date.getMinutes() - (count - i) * 5); // Data setiap 5 menit
            
            // Simulasi data historis dengan variasi SOC
            const soc = Math.floor(Math.random() * 101); // 0-100%
            
            data.push({
                id: i + 1,
                waktu: date.toLocaleString('id-ID', { 
                    year: 'numeric', 
                    month: '2-digit', 
                    day: '2-digit',
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false 
                }).replace(/\//g, '-'),
                tegangan: soc >= 100 ? (14.2 + Math.random() * 0.8).toFixed(1) : (11 + Math.random() * 4).toFixed(1),
                arus: soc >= 100 ? (0.1 + Math.random() * 0.2).toFixed(1) : (0.5 + Math.random() * 4).toFixed(1),
                soc: soc,
                suhu: soc >= 100 ? (28 + Math.random() * 7).toFixed(1) : (22 + Math.random() * 12).toFixed(1),
                relay: soc >= 100 ? 'OFF' : (Math.random() > 0.3 ? 'ON' : 'OFF')
            });
        }
        
        return data;
    },

    
    // Generate chart data based on range
    getChartData(range) {
        const points = range === 1 ? 12 : // 1 jam = 12 points (5 menit)
                      range === 6 ? 72 : // 6 jam
                      range === 24 ? 288 : // 24 jam
                      2016; // 7 hari
        
        const labels = [];
        const teganganData = [];
        const arusData = [];
        const socData = [];
        
        const now = new Date();
        
        for (let i = 0; i < points; i++) {
            const date = new Date(now);
            date.setMinutes(date.getMinutes() - (points - i) * 5);
            
            labels.push(date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));
            teganganData.push(11 + Math.random() * 4);
            arusData.push(0.5 + Math.random() * 4);
            socData.push(30 + Math.random() * 60);
        }
        
        return { labels, teganganData, arusData, socData };
    }
};

// Export untuk digunakan di file lain
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SolarData;
}