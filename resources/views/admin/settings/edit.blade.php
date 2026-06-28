<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Pengaturan Sistem" icon="fa-gears" />

    <main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        @if(session('success'))
            <div
                class="p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center w-9 h-9 bg-white border border-brand-light text-brand-gray hover:text-brand-dark hover:border-brand-primary/40 rounded-xl shadow-sm transition-all hover:scale-105"
                title="Kembali ke Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Konfigurasi Aplikasi</h2>
                <p class="text-xs text-brand-gray mt-0.5">Atur parameter dasar aplikasi seperti nama instansi dan zona
                    waktu.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_rumah_sakit" class="block text-sm font-semibold text-brand-dark mb-1.5">Nama Rumah
                        Sakit</label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-hospital-user text-xs"></i>
                        </span>
                        <input type="text" id="nama_rumah_sakit" name="nama_rumah_sakit"
                            value="{{ old('nama_rumah_sakit', $nama_rumah_sakit) }}" required
                            class="block w-full pl-10 pr-4 py-2.5 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200"
                            placeholder="Nama Rumah Sakit / Instansi Kesehatan">
                    </div>
                    @error('nama_rumah_sakit')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bg_login" class="block text-sm font-semibold text-brand-dark mb-1.5">Foto Latar Belakang
                        Halaman Login</label>

                    @if($bg_login)
                        <div
                            class="mb-2.5 relative w-32 h-20 rounded-lg overflow-hidden border border-brand-light shadow-sm">
                            <img src="{{ asset('storage/' . $bg_login) }}" alt="Preview Login"
                                class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-image text-xs"></i>
                        </span>
                        <input type="file" id="bg_login" name="bg_login" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full pl-10 pr-4 py-2 border border-brand-light rounded-xl text-brand-dark bg-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-primary/10 file:text-brand-primary hover:file:bg-brand-primary/20 cursor-pointer">
                    </div>
                    <p class="text-[10px] text-brand-gray mt-1.5 leading-relaxed">
                        <i class="fa-solid fa-circle-info"></i> Rekomendasi rasio gambar lanskap 16:9 dengan resolusi
                        minimal 1280x720 pixel (Maksimal 5MB).
                    </p>
                    @error('bg_login')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-semibold text-brand-dark mb-1.5">Zona Waktu
                        (Timezone)</label>
                    <div class="relative flex items-center">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray z-10">
                            <i class="fa-solid fa-earth-asia text-xs"></i>
                        </span>

                        <select id="timezone" name="timezone" required
                            class="block w-full pl-10 pr-10 py-2.5 border border-brand-light bg-white rounded-xl text-brand-dark text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200 appearance-none cursor-pointer h-[42px]">
                            @foreach($timezones as $tzKey => $tzLabel)
                                <option value="{{ $tzKey }}" {{ old('timezone', $timezone) === $tzKey ? 'selected' : '' }}>
                                    {{ $tzLabel }}</option>
                            @endforeach
                        </select>

                        <span
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-brand-gray z-10">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                    <p class="text-[10px] text-brand-gray mt-1.5 leading-relaxed">
                        <i class="fa-solid fa-circle-info"></i> Perubahan zona waktu akan mempengaruhi pencatatan waktu
                        pemesanan makanan pasien dan kalkulasi tanggal hari ini.
                    </p>
                    @error('timezone')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-brand-light flex items-center justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-brand-snow text-brand-gray hover:text-brand-dark font-semibold rounded-xl border border-brand-light transition-all duration-200 text-xs">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-brand-primary to-brand-accent hover:opacity-95 active:scale-95 text-brand-snow font-bold rounded-xl shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/35 transition-all duration-200 cursor-pointer text-xs">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>