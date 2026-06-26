<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Admin - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Manajemen Admin" icon="fa-user-shield" />
    <x-layout.subnav />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Daftar Akun Admin</h2>
                <p class="text-xs text-brand-gray mt-0.5">Kelola seluruh administrator rumah sakit yang bertugas di aplikasi ini.</p>
            </div>
            <a href="{{ route('superadmin.admins.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Admin
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Username</th>
                            <th class="py-3.5 px-4">Dibuat Pada</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @forelse($admins as $index => $admin)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-brand-dark">{{ $admin->username }}</td>
                                <td class="py-3 px-4 text-brand-slate">{{ $admin->created_at->translatedFormat('d F Y H:i') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('superadmin.admins.edit', $admin->id) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-xs rounded-lg transition-colors border border-amber-200 shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('superadmin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium text-xs rounded-lg transition-colors border border-rose-200 shadow-sm">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-brand-gray">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-user-xmark text-3xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada akun admin yang dibuat.</p>
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
