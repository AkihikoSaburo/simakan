<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bangsal Baru - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Tambah Bangsal Baru" icon="fa-hospital" />
    <x-layout.subnav />

    <main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bangsals.index') }}" class="h-9 w-9 rounded-xl bg-white border border-brand-light flex items-center justify-center text-brand-gray hover:text-brand-dark shadow-sm transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Registrasi Bangsal Baru</h2>
                <p class="text-xs text-brand-gray mt-0.5">Tambah data bangsal/ruang rawat inap baru ke dalam database.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm p-6">
            <form method="POST" action="{{ route('admin.bangsals.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="nama_bangsal" class="block text-sm font-semibold text-brand-dark mb-1.5">Nama Bangsal</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-gray">
                            <i class="fa-solid fa-hospital-user text-xs"></i>
                        </span>
                        <input type="text" id="nama_bangsal" name="nama_bangsal" value="{{ old('nama_bangsal') }}" required
                               class="block w-full pl-10 pr-4 py-2.5 border border-brand-light rounded-xl text-brand-dark placeholder:text-brand-gray text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition duration-200"
                               placeholder="Contoh: Bougenville, Melati, Flamboyan">
                    </div>
                    @error('nama_bangsal')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-brand-light flex items-center justify-end gap-3">
                    <a href="{{ route('admin.bangsals.index') }}" class="px-5 py-2.5 border border-brand-light text-brand-slate font-bold rounded-xl hover:bg-brand-light/30 transition-colors text-xs cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-brand-primary to-brand-accent hover:opacity-95 active:scale-95 text-brand-snow font-bold rounded-xl shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/35 transition-all duration-200 cursor-pointer text-xs">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Bangsal
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>
