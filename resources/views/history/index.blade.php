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
    let totalData = 0;
    let currentFilter = { startDate: null, endDate: null };
    
    // CSRF Token untuk AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.addEventListener('DOMContentLoaded', function() {
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
            currentFilter.startDate = picker.startDate.format('YYYY-MM-DD');
            currentFilter.endDate = picker.endDate.format('YYYY-MM-DD');
        });
        
        $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            currentFilter.startDate = null;
            currentFilter.endDate = null;
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
    
    // ==================== API FUNCTIONS (REAL DATA) ====================
    
    async function loadTableData() {
        try {
            let url = `/api/history-table?page=${currentPage}&per_page=${itemsPerPage}`;
            
            if (currentFilter.startDate && currentFilter.endDate) {
                url += `&start_date=${currentFilter.startDate}&end_date=${currentFilter.endDate}`;
            }
            
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network error');
            
            const result = await response.json();
            totalData = result.total;
            const pageData = result.data;
            
            // Update table
            const tbody = document.getElementById('history-table-body');
            tbody.innerHTML = '';
            
            if (pageData.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <i class="fas fa-database text-4xl mb-3 text-gray-300"></i>
                        <p>Tidak ada data historis</p>
                    </td>
                `;
                tbody.appendChild(emptyRow);
            } else {
                const start = (currentPage - 1) * itemsPerPage;
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.suhu || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${relayClass}">${item.relay}</span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
            
            // Update pagination
            updatePagination();
        } catch (error) {
            console.error('Gagal load data:', error);
            showToast('Error', 'Gagal memuat data historis', 'error');
        }
    }
    
    async function applyFilter() {
        currentPage = 1;
        await loadTableData();
    }
    
    async function resetFilter() {
        $('#date-range').val('');
        currentFilter.startDate = null;
        currentFilter.endDate = null;
        currentPage = 1;
        await loadTableData();
        showToast('Filter Direset', 'Menampilkan semua data', 'info');
    }
    
    async function confirmDeleteAll() {
        try {
            const response = await fetch('/api/history-delete-all', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (!response.ok) throw new Error('Delete failed');
            
            const result = await response.json();
            if (result.success) {
                hideDeleteModal();
                await loadTableData();
                showToast('Sukses', 'Semua data historis berhasil dihapus', 'success');
            } else {
                showToast('Gagal', result.message || 'Gagal menghapus data', 'error');
            }
        } catch (error) {
            console.error('Error deleting data:', error);
            showToast('Error', 'Terjadi kesalahan saat menghapus data', 'error');
        }
    }
    
    // ==================== PAGINATION ====================
    
    function updatePagination() {
        const totalPages = Math.ceil(totalData / itemsPerPage);
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, totalData);
        
        document.getElementById('page-start').textContent = totalData ? start : 0;
        document.getElementById('page-end').textContent = totalData ? end : 0;
        document.getElementById('total-items').textContent = totalData;
        
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
        const prevMobile = document.getElementById('prev-mobile');
        const nextMobile = document.getElementById('next-mobile');
        if (prevMobile) prevMobile.disabled = currentPage === 1 || totalPages === 0;
        if (nextMobile) nextMobile.disabled = currentPage === totalPages || totalPages === 0;
        
        if (prevMobile) prevMobile.onclick = () => goToPage(currentPage - 1);
        if (nextMobile) nextMobile.onclick = () => goToPage(currentPage + 1);
    }
    
    function goToPage(page) {
        const totalPages = Math.ceil(totalData / itemsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        loadTableData();
    }
    
    // ==================== EXPORT FUNCTIONS ====================
    
    function buildExportRows(data) {
        return data.map((item, index) => ({
            no: index + 1,
            waktu: item.waktu ?? '-',
            tegangan: item.voltage_v ?? '-',
            arus: item.current_a ?? '-',
            soc: item.soc_percent ?? '-',
            suhu: item.temperature_c ?? '-',
            relay: item.relay_status ?? '-'
        }));
    }
    
    async function fetchExportData() {
        let url = '/api/export-excel?';
        if (currentFilter.startDate && currentFilter.endDate) {
            url += `start_date=${currentFilter.startDate}&end_date=${currentFilter.endDate}`;
        }
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Export failed');
        
        const result = await response.json();
        return result.data || [];
    }
    
    async function exportToExcel() {
        try {
            showToast('Memproses', 'Sedang menyiapkan file Excel...', 'info');
            
            const data = await fetchExportData();
            if (!data.length) {
                showToast('Informasi', 'Tidak ada data untuk diekspor', 'warning');
                return;
            }
            
            const rows = buildExportRows(data).map(row => ({
                'No': row.no,
                'Waktu': row.waktu,
                'Tegangan (V)': row.tegangan,
                'Arus (A)': row.arus,
                'SOC (%)': row.soc,
                'Suhu (°C)': row.suhu,
                'Status Relay': row.relay
            }));
            
            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Historis');
            
            const filename = `data_historis_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(workbook, filename);
            
            showToast('Export Berhasil', `File Excel berhasil didownload (${data.length} data)`, 'success');
        } catch (error) {
            console.error('Export error:', error);
            showToast('Gagal', 'Gagal mengekspor data ke Excel', 'error');
        }
    }
    
    async function exportToPDF() {
        try {
            showToast('Memproses', 'Sedang menyiapkan file PDF...', 'info');
            
            const data = await fetchExportData();
            if (!data.length) {
                showToast('Informasi', 'Tidak ada data untuk diekspor', 'warning');
                return;
            }
            
            const rows = buildExportRows(data);
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            
            doc.setFontSize(14);
            doc.text('Data Historis Solar Panel Monitoring', 14, 15);
            doc.setFontSize(10);
            doc.text(`Diekspor: ${new Date().toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })} WIB`, 14, 22);
            doc.text(`Total data: ${rows.length}`, 14, 28);
            
            doc.autoTable({
                startY: 34,
                head: [['No', 'Waktu', 'Tegangan (V)', 'Arus (A)', 'SOC (%)', 'Suhu (°C)', 'Relay']],
                body: rows.map(row => [
                    row.no,
                    row.waktu,
                    row.tegangan,
                    row.arus,
                    row.soc,
                    row.suhu,
                    row.relay
                ]),
                styles: { fontSize: 8, cellPadding: 2 },
                headStyles: { fillColor: [37, 99, 235] },
                alternateRowStyles: { fillColor: [245, 247, 250] }
            });
            
            const filename = `data_historis_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(filename);
            
            showToast('Export Berhasil', `File PDF berhasil didownload (${data.length} data)`, 'success');
        } catch (error) {
            console.error('Export error:', error);
            showToast('Gagal', 'Gagal mengekspor data ke PDF', 'error');
        }
    }
    
    // ==================== MODAL & TOAST ====================
    
    function showDeleteModal() {
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
        setTimeout(() => { if (toast.parentElement) toast.remove(); }, 3000);
    }
</script>
@endpush
