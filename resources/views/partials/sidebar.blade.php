<!-- Mobile Header dengan Hamburger yang lebih modern -->
<div class="lg:hidden fixed top-0 left-0 right-0 bg-slate-950/80 backdrop-blur border-b border-slate-800 z-30 px-4 py-3 flex items-center justify-between">
    <button id="menu-toggle" class="text-slate-200 hover:text-white focus:outline-none transition-colors">
        <i class="fas fa-bars text-2xl"></i>
    </button>
    
    <div class="flex items-center space-x-2">
        <span class="text-sm font-semibold text-slate-200">SiMoPS++</span>
        <div class="w-8 h-8 bg-blue-500/15 rounded-full flex items-center justify-center border border-blue-500/20">
            <i class="fas fa-solar-panel text-blue-300 text-sm"></i>
        </div>
    </div>
    
    <div class="w-8 h-8 bg-slate-800 rounded-full flex items-center justify-center border border-slate-700">
        <i class="fas fa-user text-slate-300 text-sm"></i>
    </div>
</div>

<div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-40 w-72 bg-slate-950 text-slate-200 shadow-2xl lg:relative lg:translate-x-0 border-r border-slate-800">
    <div class="h-full flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/15 border border-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-solar-panel text-blue-300 text-xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h2 class="text-white font-semibold text-lg">SiMoPS++</h2>
                        <p class="text-slate-400 text-xs">Panel Surya System</p>
                    </div>
                </div>
                <button id="close-sidebar" class="lg:hidden text-slate-300 hover:text-white transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        
        <div class="mx-4 mt-4 p-4 rounded-2xl bg-slate-900 border border-slate-800">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=3B82F6&color=fff&bold=true&size=128"
                         alt="User"
                         class="w-12 h-12 rounded-xl border border-slate-700">
                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-slate-900 rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-white truncate">Admin User</h3>
                    <p class="text-xs text-slate-400 truncate">admin@example.com</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs bg-green-500/10 text-green-300 px-2 py-0.5 rounded-full border border-green-500/20">Active</span>
                        <span class="text-xs bg-blue-500/10 text-blue-300 px-2 py-0.5 rounded-full border border-blue-500/20">Admin</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu dengan Icon Modern -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white border border-slate-800' : 'text-slate-200 hover:bg-slate-900 hover:text-white border border-transparent hover:border-slate-800' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-500/15 text-blue-300 border border-blue-500/20' : 'bg-slate-800/60 text-slate-300 border border-slate-800 group-hover:bg-blue-500/15 group-hover:text-blue-300 group-hover:border-blue-500/20' }}">
                    <i class="fas fa-tachometer-alt text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="font-medium">Dashboard</span>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Monitoring real-time</p>
                </div>
                @if(request()->routeIs('dashboard'))
                <div class="w-1.5 h-8 bg-blue-500 rounded-full"></div>
                @endif
            </a>
            
            <!-- Data Historis -->
            <a href="{{ route('history') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('history') ? 'bg-slate-900 text-white border border-slate-800' : 'text-slate-200 hover:bg-slate-900 hover:text-white border border-transparent hover:border-slate-800' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('history') ? 'bg-blue-500/15 text-blue-300 border border-blue-500/20' : 'bg-slate-800/60 text-slate-300 border border-slate-800 group-hover:bg-blue-500/15 group-hover:text-blue-300 group-hover:border-blue-500/20' }}">
                    <i class="fas fa-history text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="font-medium">Data Historis</span>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Lihat riwayat data</p>
                </div>
                @if(request()->routeIs('history'))
                <div class="w-1.5 h-8 bg-blue-500 rounded-full"></div>
                @endif
            </a>
            
            <!-- Settings -->
            <a href="#" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-colors text-slate-200 hover:bg-slate-900 hover:text-white border border-transparent hover:border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-slate-800/60 border border-slate-800 group-hover:bg-blue-500/15 group-hover:border-blue-500/20 flex items-center justify-center transition-colors">
                    <i class="fas fa-cog text-slate-300 group-hover:text-blue-300 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="font-medium">Settings</span>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Pengaturan sistem</p>
                </div>
            </a>
            
            <!-- Divider -->
            <div class="my-4 border-t border-slate-800"></div>
            
            <!-- Additional Menu (Optional) -->
            <div class="px-4 py-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Lainnya</p>
            </div>
            
            <!-- Help -->
            <a href="#" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-colors text-slate-200 hover:bg-slate-900 hover:text-white border border-transparent hover:border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-slate-800/60 border border-slate-800 group-hover:bg-blue-500/15 group-hover:border-blue-500/20 flex items-center justify-center transition-colors">
                    <i class="fas fa-question-circle text-slate-300 group-hover:text-blue-300 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <span class="font-medium">Bantuan</span>
                </div>
            </a>
            
            <!-- Logout -->
            <a href="#" class="group flex items-center gap-3 px-4 py-3 text-red-300 rounded-2xl hover:bg-red-500/10 border border-transparent hover:border-red-500/20 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center transition-colors">
                    <i class="fas fa-sign-out-alt text-red-300 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <span class="font-medium">Logout</span>
                </div>
            </a>
        </nav>
        
        <!-- System Info Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-solar-panel text-slate-500"></i>
                    <span>v1.0.0</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-slate-600">
                © 2026 Solar Monitor
            </div>
        </div>
    </div>
</div>

<!-- Overlay dengan efek blur -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300"></div>

<style>
#sidebar {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#sidebar-overlay {
    transition: opacity 0.3s ease;
}

#sidebar nav {
    scrollbar-width: thin;
    scrollbar-color: #475569 #0f172a;
}

#sidebar nav::-webkit-scrollbar {
    width: 4px;
}

#sidebar nav::-webkit-scrollbar-track {
    background: #0f172a;
}

#sidebar nav::-webkit-scrollbar-thumb {
    background-color: #475569;
    border-radius: 20px;
}

.group:hover .w-10.h-10 {
    transform: scale(1.05);
}

@media (max-width: 1024px) {
    .lg\:hidden.fixed {
        background-color: rgba(2, 6, 23, 0.85);
    }
    
    #sidebar {
        box-shadow: 10px 0 30px -10px rgba(0, 0, 0, 0.3);
    }
}

@media (min-width: 1024px) {
    #sidebar {
        box-shadow: none;
        border-right: 1px solid #1e293b;
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
