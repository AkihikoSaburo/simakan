<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Pengaturan Profil" icon="fa-user-gear" />

    <main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        
        <div class="bg-white rounded-3xl border border-brand-light p-6 shadow-sm">
            <h3 class="text-xl font-bold text-brand-dark mb-1">Informasi Akun Anda</h3>
            <p class="text-brand-gray text-xs mb-6">Ubah nama pengguna atau perbarui kata sandi masuk Anda di sini.</p>

            {{-- Tampilkan Alert Sukses jika ada --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('superadmin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Input Username --}}
                <div class="mb-5">
                    <label for="username" class="block mb-2 text-sm font-semibold text-brand-dark">Username Superadmin</label>
                    <input id="username" name="username" type="text" value="{{ old('username', $superadmin->username) }}"
                        class="w-full rounded-xl border border-brand-light bg-white px-4 py-3 outline-none transition focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 @error('username') border-red-500 @enderror">
                    @error('username') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <hr class="border-brand-light my-6">

                <h4 class="text-sm font-bold text-brand-dark mb-4">Ganti Password <span class="text-xs font-normal text-brand-gray">(Kosongkan jika tidak ingin diubah)</span></h4>

                {{-- Input Password Saat Ini --}}
                <div class="mb-4" x-data="{ show: false }">
                    <label for="current_password" class="block mb-2 text-sm font-semibold text-brand-dark">Password Saat Ini</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="current_password" name="current_password" class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Input Password Baru --}}
                <div class="mb-4" x-data="{ show: false }">
                    <label for="password" class="block mb-2 text-sm font-semibold text-brand-dark">Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password" class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Input Konfirmasi Password Baru --}}
                <div class="mb-6" x-data="{ show: false }">
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-brand-dark">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('superadmin.dashboard') }}" class="inline-flex items-center rounded-xl border border-brand-light px-5 py-2.5 text-sm font-medium text-brand-gray hover:bg-brand-light">Kembali</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-primary/90">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-layout.footer />
</body>
</html>