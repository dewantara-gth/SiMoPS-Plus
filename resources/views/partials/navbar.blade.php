<nav class="bg-white shadow-sm lg:shadow-none lg:bg-transparent">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-end lg:justify-between">
            <!-- Judul Halaman (hanya muncul di desktop) -->
            <h1 class="text-xl font-semibold text-gray-800 hidden lg:block">@yield('title')</h1>
            
            <!-- Status Device dan User Menu -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-gray-100 rounded-full px-3 py-1.5">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                    <span id="device-status" class="text-sm font-medium text-gray-700">Online</span>
                </div>
                
                <!-- Notifikasi Bell -->
                <button class="relative text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                </button>
                
                <!-- User Menu -->
                <div class="relative">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-medium hidden md:block">Admin</span>
                       
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>