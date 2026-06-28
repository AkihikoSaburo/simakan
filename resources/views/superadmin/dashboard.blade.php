<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col" x-data>

    <x-layout.navbar title="Portal Superadmin" icon="fa-user-shield" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card title="Selamat Datang Superadmin 👋"
            description="Kelola administrator rumah sakit serta lakukan pemeliharaan sistem seperti backup, restore database, dan pemantauan log aplikasi." />

        <x-layout.action-grid :actions="[
        [
            'title' => 'Tambah Admin',
            'description' => 'Daftarkan administrator baru untuk rumah sakit.',
            'icon' => 'fa-user-plus',
            'modal' => 'create-admin',
        ],
        [
            'title' => 'Backup Database',
            'description' => 'Buat salinan database sebelum melakukan perubahan.',
            'icon' => 'fa-database',
            'href' => route('superadmin.database.backup'),
        ],
        [
            'title' => 'Restore Database',
            'description' => 'Pulihkan database dari file backup.',
            'icon' => 'fa-rotate-left',
            'modal' => 'restore-database',
        ],
        [
            'title' => 'Pengaturan Profil',
            'description' => 'Ubah username dan password akun superadmin Anda.',
            'icon' => 'fa-user-gear',
            'href' => route('superadmin.profile.edit'),
        ]
    ]" />

        <div class="bg-brand-snow rounded-2xl border border-brand-light shadow-sm overflow-hidden">

            <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                <div>
                    <h3 class="font-bold text-brand-dark text-lg">
                        Administrator Rumah Sakit
                    </h3>
                    <p class="text-brand-gray text-xs mt-0.5">
                        Kelola akun administrator yang memiliki akses penuh terhadap sistem.
                    </p>
                </div>

                <span
                    class="inline-flex items-center bg-brand-primary/10 text-brand-primary text-xs font-bold px-3 py-1 rounded-full">
                    {{ $admins->count() }} Admin
                </span>
            </div>

            <div class="overflow-x-auto">
                <div class="p-5">
                    @forelse($admins as $admin)
                        <div
                            class="group flex flex-col md:flex-row md:items-center md:justify-between gap-5 rounded-2xl border border-brand-light bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-primary/30 hover:shadow-md">

                            {{-- Informasi Admin --}}
                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-primary/10 text-brand-primary transition-colors duration-300 group-hover:bg-brand-primary group-hover:text-white">
                                    <i class="fa-solid fa-user-shield text-xl"></i>
                                </div>

                                <div>
                                    <h4 class="text-lg font-bold text-brand-dark">
                                        {{ $admin->username }}
                                    </h4>

                                    <p class="text-sm text-brand-gray">
                                        Administrator Rumah Sakit
                                    </p>

                                    <div class="mt-2 flex items-center gap-2 text-xs text-brand-gray">
                                        <i class="fa-regular fa-clock"></i>

                                        <span>
                                            Dibuat {{ $admin->created_at->translatedFormat('d F Y • H:i') }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- Tombol --}}
                            <div class="flex items-center gap-2">

                                {{-- Tombol Edit: Membuka modal global dan mengirim data admin via Alpine --}}
                                <button type="button" @click="$store.modal.open('edit-admin', { 
                                                                                username: '{{ $admin->username }}', 
                                                                                action: '{{ route('superadmin.administrators.update', $admin->id) }}' 
                                                                            })"
                                    class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700 transition-colors hover:bg-amber-100">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit
                                </button>

                                {{-- Form Delete dengan Route yang Tepat --}}
                                <form action="{{ route('superadmin.administrators.destroy', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" @click="$store.modal.open('delete-admin', { 
                                                            username: '{{ $admin->username }}', 
                                                            action: '{{ route('superadmin.administrators.destroy', $admin->id) }}' 
                                                        })"
                                        class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>

                        @if (!$loop->last)
                            <div class="h-4"></div>
                        @endif

                    @empty

                        <div class="flex flex-col items-center justify-center py-12 text-center">

                            <div
                                class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-light/40 text-brand-light">

                                <i class="fa-solid fa-user-shield text-2xl"></i>

                            </div>

                            <h4 class="font-semibold text-brand-dark">
                                Belum Ada Administrator
                            </h4>

                            <p class="mt-1 text-sm text-brand-gray">
                                Tambahkan administrator pertama untuk mulai mengelola rumah sakit.
                            </p>

                        </div>

                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <x-layout.footer />
    <x-superadmin.create-admin-modal />
    <x-superadmin.edit-admin-modal />
    <x-superadmin.delete-admin-modal />
    <x-superadmin.restore-database-modal />

    @if(session('openModal'))
        <div x-data x-init="$nextTick(() => $store.modal.open('{{ session('openModal') }}'))">
        </div>
    @endif

</body>

</html>