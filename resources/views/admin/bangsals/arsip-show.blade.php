<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Arsip {{ $bangsal->nama_bangsal }} - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col" x-data>

    <x-layout.navbar title="Riwayat Arsip Bangsal" icon="fa-box-archive" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.bangsals.arsip') }}"
                class="inline-flex items-center justify-center w-9 h-9 bg-white border border-brand-light text-brand-gray hover:text-brand-dark hover:border-brand-primary/40 rounded-xl shadow-sm transition-all hover:scale-105"
                title="Kembali ke Gudang Arsip">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-sm font-bold text-brand-dark">Gudang Arsip Bangsal</h2>
                <p class="text-[11px] text-brand-gray">Kembali ke daftar kartu arsip nonaktif.</p>
            </div>
        </div>

        <x-ui.welcome-card 
            title="Riwayat Pesanan: {{ $bangsal->nama_bangsal }} 🗂️"
            description="Menampilkan seluruh log arsip digital dari formulir permintaan makanan yang pernah diajukan oleh {{ $bangsal->nama_bangsal }} sebelum dinonaktifkan pada {{ $bangsal->deleted_at->translatedFormat('d F Y') }}."
            :button-text="'Kembali ke Dashboard'" 
            :button-route="route('admin.dashboard')"
            :button-icon="'fa-solid fa-chart-line'" 
        />

        <x-bangsal.order-history :orders="$orders" :bangsal-name="$bangsal->nama_bangsal" />

    </main>

    <x-layout.footer />

</body>

</html>