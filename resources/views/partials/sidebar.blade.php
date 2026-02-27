<!-- Mobile Header dengan Hamburger yang lebih modern -->
<div class="lg:hidden fixed top-0 left-0 right-0 bg-white shadow-md z-30 px-4 py-3 flex items-center justify-between">
    <button id="menu-toggle" class="text-gray-700 hover:text-blue-600 focus:outline-none transition-colors">
        <i class="fas fa-bars text-2xl"></i>
    </button>
    
    <div class="flex items-center space-x-2">
        <span class="text-sm font-medium text-gray-600">SiMoPS++</span>
        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fas fa-solar-panel text-blue-600 text-sm"></i>
        </div>
    </div>
    
    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
        <i class="fas fa-user text-gray-600 text-sm"></i>
    </div>
</div>

<!-- Sidebar Modern dengan Desain Glassmorphism -->
<div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-40 w-72 bg-white/90 backdrop-blur-lg shadow-2xl lg:relative lg:translate-x-0 lg:backdrop-blur-none lg:bg-white">
    <div class="h-full flex flex-col">
        <!-- Header Sidebar dengan Gradient -->
        <div class="relative h-32 bg-gradient-to-br from-blue-600 to-blue-800 overflow-hidden">
            <!-- Pattern Background -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 100 M100 0 L0 100" stroke="white" stroke-width="2"/>
                </svg>
            </div>
            
            <!-- Logo dan Close Button -->
            <div class="relative h-full flex items-center justify-between px-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <i class="fas fa-solar-panel text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-semibold text-lg">SiMoPS++</h2>
                        <p class="text-blue-100 text-xs">Panel Surya System</p>
                    </div>
                </div>
                
                <!-- Close Button untuk Mobile -->
                <button id="close-sidebar" class="lg:hidden text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- User Info Card -->
        <div class="mx-4 mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=3B82F6&color=fff&bold=true&size=128" 
                         alt="User" 
                         class="w-12 h-12 rounded-xl border-2 border-white shadow-md">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">Admin User</h3>
                    <p class="text-xs text-gray-500">admin@example.com</p>
                    <div class="flex items-center mt-1 space-x-2">
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Active</span>
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Admin</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu dengan Icon Modern -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
<a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 shadow-sm' : '' }}">
    <div class="relative">
        <div class="w-10 h-10 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} flex items-center justify-center transition-colors duration-200">
            <i class="fas fa-tachometer-alt {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }} text-lg"></i>
        </div>
        <!-- HAPUS BAGIAN INI YANG MEMBUAT ANGKA 1 
        {{ request()->routeIs('dashboard') && 
        '<div class="absolute -right-1 -top-1 w-3 h-3 bg-blue-600 rounded-full border-2 border-white"></div>' }}
        -->
    </div>
    <div class="ml-3 flex-1">
        <span class="font-medium">Dashboard</span>
        <p class="text-xs text-gray-400 mt-0.5">Monitoring real-time</p>
    </div>
    @if(request()->routeIs('dashboard'))
    <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
    @endif
</a>
            
            <!-- Data Historis -->
            <a href="{{ route('history') }}" class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 {{ request()->routeIs('history') ? 'bg-blue-50 text-blue-600 shadow-sm' : '' }}">
                <div class="relative">
                    <div class="w-10 h-10 rounded-lg {{ request()->routeIs('history') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-history {{ request()->routeIs('history') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }} text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <span class="font-medium">Data Historis</span>
                    <p class="text-xs text-gray-400 mt-0.5">Lihat riwayat data</p>
                </div>
                @if(request()->routeIs('history'))
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                @endif
            </a>
            
            <!-- Settings -->
            <a href="#" class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-blue-100 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-cog text-gray-500 group-hover:text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <span class="font-medium">Settings</span>
                    <p class="text-xs text-gray-400 mt-0.5">Pengaturan sistem</p>
                </div>
            </a>
            
            <!-- Divider -->
            <div class="my-4 border-t border-gray-200"></div>
            
            <!-- Additional Menu (Optional) -->
            <div class="px-4 py-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</p>
            </div>
            
            <!-- Help -->
            <a href="#" class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-blue-100 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-question-circle text-gray-500 group-hover:text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <span class="font-medium">Bantuan</span>
                </div>
            </a>
            
            <!-- Logout -->
            <a href="#" class="group flex items-center px-4 py-3 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200">
                <div class="w-10 h-10 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-sign-out-alt text-red-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <span class="font-medium">Logout</span>
                </div>
            </a>
        </nav>
        
        <!-- System Info Footer -->
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-solar-panel text-gray-400"></i>
                    <span>v1.0.0</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                © 2026 Solar Monitor
            </div>
        </div>
    </div>
</div>

<!-- Overlay dengan efek blur -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300"></div>

<style>
/* Animasi untuk sidebar */
#sidebar {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#sidebar-overlay {
    transition: opacity 0.3s ease;
}

/* Hide scrollbar tapi tetap bisa scroll */
#sidebar nav {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

#sidebar nav::-webkit-scrollbar {
    width: 4px;
}

#sidebar nav::-webkit-scrollbar-track {
    background: #f7fafc;
}

#sidebar nav::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 20px;
}

/* Active menu indicator animation */
.bg-blue-50 {
    animation: slideIn 0.2s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Hover effect untuk menu items */
.group:hover .w-10.h-10 {
    transform: scale(1.05);
}

/* Mobile specific */
@media (max-width: 1024px) {
    /* Header fixed dengan shadow */
    .lg\:hidden.fixed {
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    
    /* Sidebar di mobile lebih tinggi */
    #sidebar {
        box-shadow: 10px 0 30px -10px rgba(0, 0, 0, 0.3);
    }
    
    /* Active menu indicator */
    .bg-blue-50 {
        border-left: 3px solid #3b82f6;
    }
}

/* Desktop specific */
@media (min-width: 1024px) {
    #sidebar {
        box-shadow: none;
        border-right: 1px solid #e5e7eb;
    }
    
    /* Hover effect di desktop */
    .group:hover .bg-gray-100 {
        background-color: #dbeafe;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const closeSidebar = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;
    
    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        body.style.overflow = 'hidden';
        
        // Animasi masuk
        setTimeout(() => {
            overlay.style.opacity = '1';
        }, 10);
    }
    
    function closeSidebarFunc() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        body.style.overflow = '';
        overlay.style.opacity = '0';
    }
    
    if (menuToggle) {
        menuToggle.addEventListener('click', openSidebar);
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeSidebarFunc);
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeSidebarFunc);
    }
    
    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebarFunc();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            body.style.overflow = '';
        } else {
            // Pastikan sidebar tertutup di mobile saat resize
            sidebar.classList.add('-translate-x-full');
        }
    });
    
    // Swipe to close (untuk mobile)
    let touchStartX = 0;
    let touchEndX = 0;
    
    sidebar.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, false);
    
    sidebar.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        const swipeThreshold = 100;
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left to close
            if (window.innerWidth < 1024) {
                closeSidebarFunc();
            }
        }
    }
});
</script>