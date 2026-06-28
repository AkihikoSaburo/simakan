<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Tambah Pengguna Baru" icon="fa-users" />

    <main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="h-9 w-9 rounded-xl bg-white border border-brand-light flex items-center justify-center text-brand-gray hover:text-brand-dark shadow-sm transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Pendaftaran Pengguna Baru</h2>
                <p class="text-xs text-brand-gray mt-0.5">Daftarkan akun Dapur atau Bangsal baru untuk menggunakan aplikasi.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-semibold text-brand-dark mb-1.5">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                               class="block w-full pl-10 pr-4 py-2.5 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200"
                               placeholder="Masukkan username pengguna">
                    </div>
                    @error('username')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-brand-dark mb-1.5">Tipe Akun (Role)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-user-tag text-xs"></i>
                        </span>
                        <select id="role" name="role" required onchange="handleRoleChange()"
                                class="block w-full pl-10 pr-4 py-2.5 border border-brand-light bg-brand-snow rounded-xl text-brand-dark text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 appearance-none">
                            <option value="">-- Pilih Tipe Akun --</option>
                            <option value="dapur" {{ old('role') === 'dapur' ? 'selected' : '' }}>Dapur</option>
                            <option value="bangsal" {{ old('role') === 'bangsal' ? 'selected' : '' }}>Bangsal</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                    @error('role')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div id="bangsal-wrapper" class="{{ old('role') === 'bangsal' ? '' : 'hidden' }}">
                    <label for="bangsal_id" class="block text-sm font-semibold text-brand-dark mb-1.5">Nama Bangsal</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-hospital-user text-xs"></i>
                        </span>
                        <select id="bangsal_id" name="bangsal_id"
                                class="block w-full pl-10 pr-4 py-2.5 border border-brand-light bg-brand-snow rounded-xl text-brand-dark text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 appearance-none">
                            <option value="">-- Pilih Bangsal --</option>
                            @foreach($bangsals as $bangsal)
                                <option value="{{ $bangsal->id }}" {{ old('bangsal_id') == $bangsal->id ? 'selected' : '' }}>{{ $bangsal->nama_bangsal }}</option>
                            @endforeach
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                    @error('bangsal_id')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-brand-dark mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="block w-full pl-10 pr-10 py-2.5 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200"
                               placeholder="Minimal 6 karakter">
                        <button type="button" onclick="togglePassVisibility('password', 'eyeIcon')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-brand-gray hover:text-brand-dark transition-colors">
                            <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-brand-light flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-brand-light text-brand-slate font-bold rounded-xl hover:bg-brand-light/30 transition-colors text-xs cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-brand-primary to-brand-accent hover:opacity-95 active:scale-95 text-brand-snow font-bold rounded-xl shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/35 transition-all duration-200 cursor-pointer text-xs">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-layout.footer />

    <script>
        function handleRoleChange() {
            const roleSelect = document.getElementById('role');
            const bangsalWrapper = document.getElementById('bangsal-wrapper');
            const bangsalSelect = document.getElementById('bangsal_id');

            if (roleSelect.value === 'bangsal') {
                bangsalWrapper.classList.remove('hidden');
                bangsalSelect.setAttribute('required', 'required');
            } else {
                bangsalWrapper.classList.add('hidden');
                bangsalSelect.removeAttribute('required');
                bangsalSelect.value = '';
            }
        }

        function togglePassVisibility(inputId, iconId) {
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
