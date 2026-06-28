<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Portal Admin" icon="fa-user-tie" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card title="Selamat Datang Admin 👋"
            description="Melalui portal ini, Anda dapat mengelola akun dapur, akun bangsal, daftar bangsal (ruang rawat inap), serta memantau pesanan makanan pasien hari ini." />
        <x-layout.action-grid :actions="[
        [
            'title' => 'Kelola Pengguna',
            'description' => 'Manajemen data pengguna, hak akses, dan akun staf rumah sakit.',
            'icon' => 'fa-users',
            'color' => 'default', // Menggunakan warna default (putih) karena ini menu navigasi/indeks utama
            'href' => route('admin.users.index'),
        ],
        [
            'title' => 'Daftar Bangsal',
            'description' => 'Kelola ruangan, kapasitas kasur, dan distribusi bangsal pasien.',
            'icon' => 'fa-hospital', // Diganti ke fa-hospital agar lebih relevan dengan bangsal/ruangan
            'color' => 'default', // Navigasi standar, lebih rapi jika disamakan dengan Kelola Pengguna
            'href' => route('admin.bangsals.index'),
        ],
        [
            'title' => 'Pengaturan Sistem',
            'description' => 'Konfigurasi parameter aplikasi, integrasi, dan preferensi sistem.',
            'icon' => 'fa-sliders', // Diganti ke fa-sliders atau fa-gear yang lebih cocok untuk pengaturan
            'color' => 'info', // Biru/Info cocok untuk menu utilitas/konfigurasi sistem
            'href' => route('admin.settings.edit'),
        ]
    ]" />

        <x-layout.stats-grid :stats="[
        [
            'title' => 'Akun Dapur',
            'value' => $dapurCount,
        ],
        [
            'title' => 'Akun Bangsal',
            'value' => $bangsalCount,
        ],
        [
            'title' => 'Total Bangsal',
            'value' => $wardsCount,
        ],
        [
            'title' => 'Pesanan Hari Ini',
            'value' => $todayOrdersCount,
        ],
    ]" />

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                <div>
                    <h3 class="font-bold text-brand-dark text-lg">Pesanan Makanan Terkini</h3>
                    <p class="text-brand-gray text-xs mt-0.5">Berikut adalah 5 pesanan makanan terakhir yang dimasukkan
                        oleh petugas bangsal hari ini</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Petugas Bangsal</th>
                            <th class="py-3.5 px-4">Nama Bangsal</th>
                            <th class="py-3.5 px-4">Waktu Pemesanan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @forelse($recentOrders as $index => $order)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-brand-dark">{{ $order->creator->username ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-brand-slate">{{ $order->bangsal->nama_bangsal ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-brand-dark flex items-center gap-1.5">
                                            <i class="fa-regular fa-clock text-xs text-brand-gray"></i>
                                            {{ $order->created_at->diffForHumans() }}
                                        </span>
                                        <span class="text-xs text-brand-gray mt-0.5 pl-5">
                                            {{-- ->format('T') akan otomatis memunculkan WIB/WITA/WIT sesuai timezone
                                            aplikasi --}}
                                            {{ $order->created_at->translatedFormat('H:i') }}
                                            {{ $order->created_at->format('T') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('dapur.orders.show', $order->id) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-brand-light/20 hover:bg-brand-light text-brand-primary font-medium text-xs rounded-lg transition-colors border border-brand-light shadow-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-brand-gray">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-clipboard-list text-3xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada pesanan makanan yang terdata hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>