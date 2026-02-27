@extends('layouts.app')

@section('title', 'Data Historis')

@section('content')
<div class="space-y-4">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                <input type="text" id="date-range" class="w-full border rounded-lg px-3 py-2" placeholder="Pilih rentang tanggal">
            </div>
            <div class="flex gap-2">
                <button id="filter-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button id="reset-btn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
            </div>
        </div>
        
        <!-- Export dan Delete Buttons -->
        <div class="flex flex-col md:flex-row gap-2 mt-4">
            <button id="export-excel" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center justify-center">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <button id="export-pdf" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center justify-center">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
            <button id="delete-all" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center justify-center md:ml-auto">
                <i class="fas fa-trash mr-2"></i>Hapus Semua Data
            </button>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tegangan (V)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arus (A)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOC (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suhu (°C)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Relay</th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Info dan Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button id="prev-mobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button id="next-mobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span id="page-start">1</span> - <span id="page-end">10</span> dari <span id="total-items">0</span> data
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" id="pagination-links">
                        <!-- Pagination links akan diisi JavaScript -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Semua Data -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Hapus Semua Data</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus <span class="font-semibold" id="total-data-to-delete">0</span> data historis?
                    <br>Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center gap-3 mt-4">
                <button id="cancel-delete" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Hapus Semua
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let itemsPerPage = 10;
    let filteredData = [];
    let allData = [];
    let currentFilter = { start: null, end: null };
    
    document.addEventListener('DOMContentLoaded', function() {
        // Generate dummy data dan urutkan dari terbaru ke terlama
        allData = SolarData.generateHistoricalData(150);
        sortDataByDate('desc'); // Urutkan descending (terbaru dulu)
        filteredData = [...allData];
        
        // Inisialisasi date range picker
        $('#date-range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
        
        $('#date-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            currentFilter.start = picker.startDate;
            currentFilter.end = picker.endDate;
        });
        
        $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            currentFilter.start = null;
            currentFilter.end = null;
        });
        
        // Event listeners
        document.getElementById('filter-btn').addEventListener('click', applyFilter);
        document.getElementById('reset-btn').addEventListener('click', resetFilter);
        document.getElementById('export-excel').addEventListener('click', exportToExcel);
        document.getElementById('export-pdf').addEventListener('click', exportToPDF);
        document.getElementById('delete-all').addEventListener('click', showDeleteModal);
        
        // Modal events
        document.getElementById('cancel-delete').addEventListener('click', hideDeleteModal);
        document.getElementById('confirm-delete').addEventListener('click', confirmDeleteAll);
        
        // Load initial data
        loadTableData();
    });
    
    // Fungsi untuk mengurutkan data berdasarkan waktu
    function sortDataByDate(order = 'desc') {
        allData.sort((a, b) => {
            const dateA = new Date(a.waktu.replace(/-/g, '/'));
            const dateB = new Date(b.waktu.replace(/-/g, '/'));
            return order === 'desc' ? dateB - dateA : dateA - dateB;
        });
    }
    
    function applyFilter() {
        if (currentFilter.start && currentFilter.end) {
            filteredData = allData.filter(item => {
                const itemDate = moment(item.waktu, 'YYYY-MM-DD HH:mm:ss');
                return itemDate.isBetween(currentFilter.start, currentFilter.end, 'day', '[]');
            });
        } else {
            filteredData = [...allData];
        }
        currentPage = 1;
        loadTableData();
    }
    
    function resetFilter() {
        $('#date-range').val('');
        currentFilter.start = null;
        currentFilter.end = null;
        filteredData = [...allData];
        currentPage = 1;
        loadTableData();
    }
    
    function loadTableData() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageData = filteredData.slice(start, end);
        
        // Update table
        const tbody = document.getElementById('history-table-body');
        tbody.innerHTML = '';
        
        if (pageData.length === 0) {
            // Tampilkan pesan jika tidak ada data
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                    <i class="fas fa-database text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada data historis</p>
                </td>
            `;
            tbody.appendChild(emptyRow);
        } else {
            pageData.forEach((item, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors';
                const relayClass = item.relay === 'ON' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const no = start + index + 1;
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${no}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.waktu}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.tegangan}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.arus}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.soc}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.suhu}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${relayClass}">${item.relay}</span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Update pagination
        updatePagination();
    }
    
    function showDeleteModal() {
        const totalData = filteredData.length;
        
        if (totalData === 0) {
            showToast('Informasi', 'Tidak ada data untuk dihapus', 'info');
            return;
        }
        
        document.getElementById('total-data-to-delete').textContent = totalData;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
    
    function confirmDeleteAll() {
        // Hapus semua data
        allData = [];
        filteredData = [];
        
        // Reset ke halaman pertama
        currentPage = 1;
        
        // Reload table
        loadTableData();
        
        // Sembunyikan modal
        hideDeleteModal();
        
        // Tampilkan notifikasi
        showToast('Sukses', 'Semua data historis berhasil dihapus', 'success');
        
        // Optional: Simpan ke localStorage jika ingin persistent
        // localStorage.removeItem('solar_historical_data');
    }
    
    function updatePagination() {
        const totalItems = filteredData.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, totalItems);
        
        document.getElementById('page-start').textContent = totalItems ? start : 0;
        document.getElementById('page-end').textContent = totalItems ? end : 0;
        document.getElementById('total-items').textContent = totalItems;
        
        // Generate pagination links
        let paginationHtml = '';
        
        if (totalPages > 0) {
            // Previous button
            paginationHtml += `
                <button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} 
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'}">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
            `;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHtml += `
                        <button onclick="goToPage(${i})" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${i === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHtml += `
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                    `;
                }
            }
            
            // Next button
            paginationHtml += `
                <button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} 
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium ${currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'}">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            `;
        }
        
        document.getElementById('pagination-links').innerHTML = paginationHtml;
        
        // Mobile buttons
        document.getElementById('prev-mobile').disabled = currentPage === 1 || totalPages === 0;
        document.getElementById('next-mobile').disabled = currentPage === totalPages || totalPages === 0;
        
        // Mobile buttons events
        document.getElementById('prev-mobile').onclick = () => goToPage(currentPage - 1);
        document.getElementById('next-mobile').onclick = () => goToPage(currentPage + 1);
    }
    
    function goToPage(page) {
        if (page < 1 || page > Math.ceil(filteredData.length / itemsPerPage)) return;
        currentPage = page;
        loadTableData();
    }
    
    function exportToExcel() {
        if (filteredData.length === 0) {
            showToast('Peringatan', 'Tidak ada data untuk diekspor', 'warning');
            return;
        }
        
        const dataToExport = filteredData.map((item, index) => ({
            No: index + 1,
            Waktu: item.waktu,
            Tegangan: item.tegangan + ' V',
            Arus: item.arus + ' A',
            SOC: item.soc + '%',
            Suhu: item.suhu + '°C',
            'Status Relay': item.relay
        }));
        
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(dataToExport);
        XLSX.utils.book_append_sheet(wb, ws, 'Data Historis');
        XLSX.writeFile(wb, `data_historis_${new Date().toISOString().split('T')[0]}.xlsx`);
        
        showToast('Export Berhasil', 'File Excel berhasil didownload', 'success');
    }
    
    function exportToPDF() {
        if (filteredData.length === 0) {
            showToast('Peringatan', 'Tidak ada data untuk diekspor', 'warning');
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        doc.setFontSize(16);
        doc.text('Data Historis Panel Surya', 14, 15);
        doc.setFontSize(10);
        doc.text(`Tanggal Export: ${new Date().toLocaleDateString('id-ID')}`, 14, 22);
        doc.text(`Total Data: ${filteredData.length}`, 14, 28);
        
        const tableData = filteredData.map((item, index) => [
            index + 1,
            item.waktu,
            item.tegangan + ' V',
            item.arus + ' A',
            item.soc + '%',
            item.suhu + '°C',
            item.relay
        ]);
        
        doc.autoTable({
            head: [['No', 'Waktu', 'Tegangan', 'Arus', 'SOC', 'Suhu', 'Relay']],
            body: tableData,
            startY: 35,
            styles: { fontSize: 8 },
            headStyles: { fillColor: [41, 128, 185] },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        
        doc.save(`data_historis_${new Date().toISOString().split('T')[0]}.pdf`);
        
        showToast('Export Berhasil', 'File PDF berhasil didownload', 'success');
    }
    
    function showToast(title, message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
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
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
</script>
@endpush