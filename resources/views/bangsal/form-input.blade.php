<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Permintaan Makanan - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar 
    title="Digitalisasi Form Makanan" 
    role="Bangsal" 
    :username="auth()->user()->username" 
    icon="fa-user-nurse" 
    />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <form action="{{ route('bangsal.orders.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-brand-snow rounded-2xl border border-brand-light shadow-sm overflow-hidden">
                <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                    <div>
                        <h3 class="font-bold text-brand-dark text-lg">Daftar Permintaan Pasien</h3>
                        <p class="text-brand-gray text-xs mt-0.5">Isi detail diet makanan pasien per baris secara lengkap</p>
                    </div>
                    <button type="button" onclick="tambahBarisPasien()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-primary hover:bg-brand-primary/95 text-brand-snow font-bold text-xs rounded-xl shadow-md transition-colors">
                        <i class="fa-solid fa-plus"></i> Tambah Pasien
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="tabelPasien">
                        <thead>
                            <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4 min-w-[200px]">Nama Pasien</th>
                                <th class="py-3.5 px-4 min-w-[130px]">No. RM</th>
                                <th class="py-3.5 px-4 min-w-[120px]">Kamar / Kelas</th>
                                <th class="py-3.5 px-4 min-w-[160px]">Bentuk Makanan</th>
                                <th class="py-3.5 px-4 min-w-[180px]">Jenis Diet</th>
                                <th class="py-3.5 px-4 min-w-[180px]">Keterangan Tambahan</th>
                                <th class="py-3.5 px-4 w-12 text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-light text-sm">
                            
                            <tr class="hover:bg-brand-light/5 transition-colors row-pasien">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray index-nomor">1</td>
                                <td class="py-3 px-3">
                                    <input type="text" name="nama_pasien[]" required placeholder="Nama Lengkap" 
                                        class="form-input">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="no_rm[]" required placeholder="00-00-00" 
                                        class="form-input font-mono">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="kamar_kelas[]" required placeholder="Kmr 3 / Klst II" 
                                        class="form-input">
                                </td>
                                <td class="py-3 px-3 relative wrapper-bentuk-makanan">
                                    <button type="button" 
                                        class="btn-dropdown form-input flex items-center justify-between">
                                        <span class="label-dropdown truncate text-brand-gray pointer-events-none">Pilih Bentuk Makanan</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-brand-gray ml-1 pointer-events-none"></i>
                                    </button>
                                    <div class="menu-dropdown hidden fixed bg-white border border-brand-light rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto p-2 space-y-1">
                                        <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                                            <input type="checkbox" name="bentuk_makanan[0][]" value="Nasi" class="form-checkbox-brand"> Nasi
                                        </label>
                                        <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                                            <input type="checkbox" name="bentuk_makanan[0][]" value="Bubur" class="form-checkbox-brand"> Bubur
                                        </label>
                                        <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                                            <input type="checkbox" name="bentuk_makanan[0][]" value="Msk. Cair / Susu" class="form-checkbox-brand"> Msk. Cair / Susu
                                        </label>
                                        <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                                            <input type="checkbox" name="bentuk_makanan[0][]" value="Bubur Saring" class="form-checkbox-brand"> Bubur Saring
                                        </label>
                                        <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                                            <input type="checkbox" name="bentuk_makanan[0][]" value="Sonde" class="form-checkbox-brand"> Sonde
                                        </label>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="diet[]" placeholder="Contoh: RG (Rendah Garam), DM" 
                                        class="form-input">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="keterangan[]" placeholder="Contoh: Tanpa Telur, Alergi" 
                                        class="form-input">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button" disabled class="text-brand-gray/40 cursor-not-allowed text-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('bangsal.dashboard') }}" class="px-6 py-3 border border-brand-light text-brand-slate font-bold rounded-xl hover:bg-brand-light/30 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit" class="px-7 py-3 bg-brand-primary hover:bg-brand-primary/95 text-brand-snow font-bold rounded-xl shadow-lg shadow-brand-light transition-all active:transform active:scale-95 text-sm">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Ke Dapur Gizi
                </button>
            </div>

        </form>
    </main>

    <x-layout.footer />
    
</body>
</html>