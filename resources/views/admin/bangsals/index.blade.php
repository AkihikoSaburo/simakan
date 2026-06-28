<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Manajemen Bangsal" icon="fa-hospital" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center justify-center w-9 h-9 bg-white border border-brand-light text-brand-gray hover:text-brand-dark hover:border-brand-primary/40 rounded-xl shadow-sm transition-all hover:scale-105"
                    title="Kembali ke Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="text-xl font-extrabold tracking-tight text-brand-dark">Daftar Ruang Bangsal</h2>
                <p class="text-xs text-brand-gray mt-0.5">Kelola seluruh daftar bangsal/ruang rawat inap di rumah sakit.</p>
            </div>
            <a href="{{ route('admin.bangsals.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Bangsal
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Bangsal</th>
                            <th class="py-3.5 px-4">Ditambahkan Pada</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @forelse($bangsals as $index => $bangsal)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-brand-dark">{{ $bangsal->nama_bangsal }}</td>
                                <td class="py-3 px-4 text-brand-slate">{{ $bangsal->created_at->translatedFormat('d F Y H:i') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.bangsals.edit', $bangsal->id) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-xs rounded-lg transition-colors border border-amber-200 shadow-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.bangsals.destroy', $bangsal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bangsal ini? Pengguna yang terhubung dengan bangsal ini tidak akan terhapus, namun relasi bangsalnya akan di-reset.');" class="inline">
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
                                        <i class="fa-solid fa-hospital-user text-3xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada data bangsal terdaftar.</p>
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
