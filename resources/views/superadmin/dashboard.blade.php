<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Portal Superadmin" icon="fa-user-shield" />
    <x-layout.subnav />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card 
            title="Selamat Datang Superadmin 👋"
            description="Mulai kelola akun Admin Rumah Sakit yang bertugas mengelola data bangsal, akun dapur, dan konfigurasi umum sistem."
            :button-text="'Daftarkan Admin Baru'" 
            :button-route="route('superadmin.admins.create')"
            :button-icon="'fa-solid fa-user-plus'" 
        />

        <x-layout.stats-grid :stats="[
            [
                'title' => 'Total Admin',
                'value' => $adminsCount,
            ],
            [
                'title' => 'Akun Dapur',
                'value' => $dapurCount,
            ],
            [
                'title' => 'Akun Bangsal',
                'value' => $bangsalCount,
            ],
        ]" />

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                <div>
                    <h3 class="font-bold text-brand-dark text-lg">Pendaftaran Admin Terbaru</h3>
                    <p class="text-brand-gray text-xs mt-0.5">Berikut adalah daftar akun administrator yang baru dibuat</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Username</th>
                            <th class="py-3.5 px-4">Tanggal Registrasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @forelse($recentAdmins as $index => $admin)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-brand-dark">{{ $admin->username }}</td>
                                <td class="py-3 px-4 text-brand-slate">{{ $admin->created_at->translatedFormat('d F Y H:i') }} WITA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-brand-gray">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-users-slash text-2xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada akun admin terdaftar.</p>
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