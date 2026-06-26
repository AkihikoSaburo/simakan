<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autentikasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow min-h-screen flex items-center justify-center overflow-x-hidden antialiased">

    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen w-full">

        <div class="hidden md:flex flex-col justify-between p-12 lg:p-16 relative bg-cover bg-bottom"
            style="background-image: url('/images/gambar-rumah-sakit.jpeg');">

            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10 z-0"></div>

            <div class="flex items-center space-x-3 z-10">
                <span class="text-2xl font-black text-white tracking-wide drop-shadow-md">Sistem Manajemen Makanan Pasien<br>{{ $nama_rumah_sakit ?? 'RSUD Andi Makkasau' }}</span>
            </div>

            <div class="text-white text-sm z-10 flex justify-between font-medium drop-shadow-md">
                <span>Kota Parepare</span>
                <span>&copy; 2026</span>
            </div>
        </div>

        <div class="flex flex-col justify-center px-6 py-12 lg:px-16 xl:px-24 bg-white relative">

            <div id="loginState" class="mx-auto w-full max-w-md transition-all duration-300">
                <div class="mb-10 text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-brand-dark mb-2">Selamat Datang</h2>
                    <p class="text-brand-gray">Silakan masukkan email dan kata sandi Anda untuk mengakses dashboard.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="loginUser" class="block text-sm font-semibold text-brand-dark mb-2">Username</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" id="loginUser" name="username" required
                                class="block w-full pl-10 pr-4 py-3 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 text-sm"
                                placeholder="Username">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="loginPassword" class="block text-sm font-semibold text-brand-dark">Kata
                                Sandi</label>
                        </div>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" id="loginPassword" name="password" required
                                class="block w-full pl-10 pr-12 py-3 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 text-sm"
                                placeholder="••••••••">

                            <button type="button" onclick="togglePassword('loginPassword', 'eyeIconLogin')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-brand-gray hover:text-brand-dark transition-colors">
                                <i id="eyeIconLogin" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-brand-snow bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-200 active:scale-[0.98]">
                        Masuk Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function switchState(state) {
            const loginState = document.getElementById('loginState');
            const registerState = document.getElementById('registerState');

            if (state === 'register') {
                loginState.classList.add('hidden');
                registerState.classList.remove('hidden');
                document.title = "Daftar Akun";
            } else {
                registerState.classList.add('hidden');
                loginState.classList.remove('hidden');
                document.title = "Masuk";
            }
        }

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>