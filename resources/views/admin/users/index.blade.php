<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun Dapur & Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Manajemen Akun" icon="fa-users" />
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
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Daftar Akun Dapur & Bangsal</h2>
                <p class="text-xs text-brand-gray mt-0.5">Kelola akun-akun pengguna yang akan mengakses aplikasi ini.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Pengguna
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
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
                                        <span class="text-brand-primary font-bold"><i class="fa-solid fa-hospital-user mr-1 text-xs"></i> {{ $user->bangsal->nama_bangsal ?? 'Belum Ditentukan' }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-xs rounded-lg transition-colors border border-amber-200 shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');" class="inline">
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

</body>

</html>
