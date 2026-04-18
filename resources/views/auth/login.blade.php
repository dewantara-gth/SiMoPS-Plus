<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Solar Panel Monitoring</title>
    
    <!-- Tailwind CSS -->
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-slate-950 text-slate-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Solar Monitor</h2>
                <p class="text-gray-600 mt-2">Solar Panel Monitoring System</p>
            </div>
            
            <form>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Login Operator</h3>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           type="email" 
                           id="email" 
                           placeholder="admin@example.com"
                           value="admin@example.com">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           type="password" 
                           id="password" 
                           placeholder="••••••••"
                           value="password">
                </div>
                
                <button class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                    Login
                </button>
            </form>
            
            <div class="text-center mt-4">
                <button type="button" id="open-forgot-password" class="text-sm text-blue-600 hover:text-blue-800">
                    Forgot Password?
                </button>
            </div>
            
            <div class="border-t border-gray-300 mt-6 pt-6 text-center">
                <p class="text-xs text-gray-500">
                    Demo Login: admin@example.com / password
                </p>
            </div>
        </div>
    </div>

    <div id="forgot-password-modal" class="hidden fixed inset-0 z-50">
        <div id="forgot-password-backdrop" class="absolute inset-0 bg-slate-950/70"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
            <div role="dialog" aria-modal="true" aria-labelledby="forgot-password-title" class="w-full max-w-md bg-white rounded-lg shadow-xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 id="forgot-password-title" class="text-xl font-semibold text-gray-800">Ubah Password</h3>
                        <p class="text-sm text-gray-600 mt-1">Isi data di bawah untuk mengganti password.</p>
                    </div>
                    <button type="button" id="close-forgot-password" class="text-gray-500 hover:text-gray-800">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form class="mt-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot_name">Nama</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                               type="text"
                               id="forgot_name"
                               placeholder="Nama Operator">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot_email">Email</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                               type="email"
                               id="forgot_email"
                               placeholder="operator@example.com">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot_password">Password Baru</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                               type="password"
                               id="forgot_password"
                               placeholder="••••••••">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot_password_confirmation">Password Baru Lagi</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                               type="password"
                               id="forgot_password_confirmation"
                               placeholder="••••••••">
                    </div>

                    <button type="button" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openButton = document.getElementById('open-forgot-password');
            const modal = document.getElementById('forgot-password-modal');
            const backdrop = document.getElementById('forgot-password-backdrop');
            const closeButton = document.getElementById('close-forgot-password');
            const firstInput = document.getElementById('forgot_name');

            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => firstInput?.focus(), 0);
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openButton?.addEventListener('click', openModal);
            closeButton?.addEventListener('click', closeModal);
            backdrop?.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>
