<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditangguhkan - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- 1. Mengubah struktur body menjadi flex-col agar navbar dan konten utama terpisah secara vertikal --}}

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col" x-data>

    <x-layout.navbar title="Akses Terbatas" icon="fa-user-nurse" :username="auth()->user()->username" />

    {{-- 2. Kontainer pembungkus utama untuk mendorong card tepat di tengah sisa layar --}}
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6">

        <div
            class="max-w-md w-full bg-white border border-brand-light rounded-2xl p-6 sm:p-8 text-center shadow-sm space-y-6">

            <div
                class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 border border-amber-200/50">
                <i class="fa-solid fa-hospital-user text-2xl"></i>
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-bold text-brand-dark tracking-tight">Unit Bangsal Belum Ditentukan</h2>
                <p class="text-sm text-brand-gray leading-relaxed px-1">
                    Hai <span class="font-semibold text-brand-dark">{{ auth()->user()->username }}</span>, ruangan
                    penempatan Anda saat ini kosong atau bangsal sebelumnya telah dinonaktifkan oleh manajemen sistem.
                </p>
            </div>

            <div
                class="p-4 bg-brand-light/20 border border-brand-light/70 rounded-xl text-xs text-brand-slate text-left flex gap-3 leading-relaxed">
                <i class="fa-solid fa-circle-info text-brand-primary mt-0.5 text-sm flex-shrink-0"></i>
                <span>Harap hubungi <span class="font-bold text-brand-dark">Administrator Utama</span> rumah sakit untuk
                    memetakan kembali
                    akun Anda ke unit ruangan yang aktif agar dapat mengirim formulir makanan pasien.</span>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 py-2.5 px-4 bg-brand-snow hover:bg-brand-light/80 border border-brand-light text-brand-dark font-bold text-xs rounded-xl shadow-sm transition-all cursor-pointer active:scale-[0.99]">
                    <i class="fa-solid fa-right-from-bracket text-brand-gray"></i> Keluar Aplikasi
                </button>
            </form>

        </div>
    </main>

    <x-layout.footer />

</body>

</html>