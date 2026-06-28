<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun Dapur & Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col" x-data>

    <x-layout.navbar title="Manajemen Akun" icon="fa-users" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Navigasi Mundur (Konsisten di Atas) -->
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center w-9 h-9 bg-white border border-brand-light text-brand-gray hover:text-brand-dark hover:border-brand-primary/40 rounded-xl shadow-sm transition-all hover:scale-105"
                title="Kembali ke Dashboard Admin">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-sm font-bold text-brand-dark">Menu Utama Admin</h2>
                <p class="text-[11px] text-brand-gray">Kembali ke panel kendali dashboard.</p>
            </div>
        </div>

        <!-- Welcome Card dengan Fungsi Utama Tambah Data -->
        <x-ui.welcome-card 
            title="Kelola Akun Pengguna 👥"
            description="Manajemen data pengguna, pembagian hak akses kontrol rute, dan akun staf rumah sakit. Anda dapat mendaftarkan akun baru, mengubah rincian informasi, atau menghapus kredensial pengguna yang sudah tidak bertugas." 
            :button-text="'Tambah Pengguna Baru'" 
            :button-route="route('admin.users.create')"
            :button-icon="'fa-solid fa-plus'" />

        <!-- Panel Tabel Utama -->
        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                <div>
                    <h3 class="font-bold text-brand-dark text-base">Daftar Akun Pengguna Aktif</h3>
                    <p class="text-[11px] text-brand-gray mt-0.5">Menampilkan seluruh personel staf bagian dapur dan petugas instalasi bangsal saat ini.</p>
                </div>

                <span class="inline-flex items-center bg-brand-primary/10 text-brand-primary text-xs font-bold px-3 py-1 rounded-full">
                    {{ $users->count() }} Personel Aktif
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Username</th>
                            <th class="py-3.5 px-4">Tipe Akun (Role)</th>
                            <th class="py-3.5 px-4">Hak Akses Bangsal</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-brand-dark">{{ $user->username }}</td>
                                <td class="py-3 px-4">
                                    @if($user->role === 'dapur')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-utensils text-[10px]"></i> Dapur
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-user-nurse text-[10px]"></i> Bangsal
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-brand-slate font-medium">
                                    @if($user->role === 'dapur')
                                        <span class="text-brand-gray italic">Semua Bangsal (Akses Dapur)</span>
                                    @else
                                        <span class="text-brand-primary font-bold">
                                            <i class="fa-solid fa-hospital-user mr-1 text-xs"></i>
                                            {{ $user->bangsal?->nama_bangsal ?? 'Belum Ditentukan' }}
                                            @if($user->bangsal?->trashed())
                                                <span class="text-rose-500 font-medium text-xs">(Arsip)</span>
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-xs rounded-lg transition-colors border border-amber-200 shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        <!-- Tombol Hapus Kustom Alpine -->
                                        <button type="button" @click="$store.modal.open('delete-user', { 
                                                    username: '{{ $user->username }}', 
                                                    action: '{{ route('admin.users.destroy', $user->id) }}' 
                                                })" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium text-xs rounded-lg transition-colors border border-rose-200 shadow-sm cursor-pointer">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-brand-gray">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-user-slash text-3xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada akun Dapur atau Bangsal yang terdaftar.</p>
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
    
    <!-- Render Komponen Modal Bersih -->
    <x-admin.delete-user />
    
    @if(session('openModal'))
        <div x-data x-init="$nextTick(() => $store.modal.open('{{ session('openModal') }}'))">
        </div>
    @endif

</body>

</html>