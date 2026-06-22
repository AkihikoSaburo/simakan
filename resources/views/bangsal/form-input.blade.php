<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Permintaan Makanan - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Digitalisasi Form Makanan" role="Bangsal" :username="auth()->user()->username"
        icon="fa-user-nurse" />

    <main class="flex-1 w-full" x-data="{ 
              pasiens: [{ nama_pasien: '', no_rm: '', kamar_kelas: '', bentuk_makanan: [], diet: '', keterangan: '' }],
              opsiMakanan: ['Nasi', 'Bubur', 'Msk. Cair / Susu', 'Bubur Saring', 'Sonde'],
              activePasienIndex: null,
              showKonfirmasiModal: false
          }">

        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            <form autocomplete="off" x-ref="orderForm" action="{{ route('bangsal.orders.store') }}" method="POST" class="space-y-6"
                @submit.prevent="showKonfirmasiModal = true">
                @csrf
                <div class="bg-brand-snow rounded-2xl border border-brand-light shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                        <div>
                            <h3 class="font-bold text-brand-dark text-lg">Daftar Permintaan Pasien</h3>
                            <p class="text-brand-gray text-xs mt-0.5">Isi detail diet makanan pasien per baris secara
                                lengkap</p>
                        </div>
                        <button type="button"
                            @click="pasiens.push({ nama_pasien: '', no_rm: '', kamar_kelas: '', bentuk_makanan: [], diet: '', keterangan: '' })"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-primary hover:bg-brand-primary/95 text-brand-snow font-bold text-xs rounded-xl shadow-md transition-colors">
                            <i class="fa-solid fa-plus"></i> Tambah Pasien
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="tabelPasien">
                            <thead>
                                <tr
                                    class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
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
                            <tbody class="divide-y divide-brand-light text-sm transition-all duration-300 ease-in-out">

                                <template x-for="(pasien, index) in pasiens" :key="index">
                                    <x-form.row-pasien-form />
                                </template>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('bangsal.dashboard') }}"
                        class="px-6 py-3 border border-brand-light text-brand-slate font-bold rounded-xl hover:bg-brand-light/30 transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-7 py-3 bg-brand-primary hover:bg-brand-primary/95 text-brand-snow font-bold rounded-xl shadow-lg shadow-brand-light transition-all active:transform active:scale-95 text-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Ke Dapur Gizi
                    </button>
                </div>
            </form>
        </div>



        <div x-show="activePasienIndex !== null" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">

            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                x-show="activePasienIndex !== null" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="activePasienIndex = null">
            </div>

            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl border border-brand-light overflow-hidden p-6"
                x-show="activePasienIndex !== null" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <div class="flex items-center justify-between border-b border-brand-light pb-3 mb-4">
                    <div>
                        <h4 class="font-bold text-brand-dark text-base">Pilih Bentuk Makanan</h4>
                        <p class="text-xs text-brand-gray mt-0.5">
                            Pasien No. <span class="font-bold"
                                x-text="activePasienIndex !== null ? activePasienIndex + 1 : ''"></span>:
                            <span class="font-semibold text-brand-primary"
                                x-text="activePasienIndex !== null && pasiens[activePasienIndex] ? (pasiens[activePasienIndex].nama_pasien || 'Tanpa Nama') : ''"></span>
                        </p>
                    </div>
                    <button type="button" @click="activePasienIndex = null"
                        class="text-brand-gray hover:text-brand-dark transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-2 my-4">
                    <template x-if="activePasienIndex !== null && pasiens[activePasienIndex]">
                        <div>
                            <template x-for="opsi in opsiMakanan" :key="opsi">
                                <label
                                    class="flex items-center gap-3 px-3 py-2.5 hover:bg-brand-light/30 border border-transparent hover:border-brand-light rounded-xl cursor-pointer text-sm font-medium text-brand-dark transition-all">
                                    <input type="checkbox" :value="opsi"
                                        x-model="pasiens[activePasienIndex].bentuk_makanan"
                                        class="form-checkbox-brand w-4 h-4 rounded text-brand-primary focus:ring-brand-primary">
                                    <span x-text="opsi"></span>
                                </label>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="mt-5 border-t border-brand-light pt-3 flex justify-end">
                    <button type="button" @click="activePasienIndex = null"
                        class="px-5 py-2 bg-brand-primary hover:bg-brand-primary/95 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Simpan & Selesai
                    </button>
                </div>
            </div>
        </div>


        <div x-show="showKonfirmasiModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">

            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-show="showKonfirmasiModal"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="showKonfirmasiModal = false">
            </div>

            <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-xl border border-brand-light overflow-hidden p-6 text-center"
                x-show="showKonfirmasiModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <div
                    class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <h3 class="text-lg font-bold text-brand-dark">Kirim Permintaan Makanan?</h3>
                <p class="text-xs text-brand-gray mt-2 px-2">
                    Pastikan seluruh data diet dari <span class="font-bold text-brand-dark"
                        x-text="pasiens.length"></span> pasien sudah benar. Data yang dikirim akan langsung diproses
                    oleh Dapur Gizi.
                </p>

                <div class="mt-6 flex items-center justify-center gap-3">
                    <button type="button" @click="showKonfirmasiModal = false"
                        class="w-1/2 py-2.5 border border-brand-light text-brand-slate font-bold rounded-xl text-xs hover:bg-brand-light/30 transition-colors">
                        Periksa Kembali
                    </button>
                    <button type="button" @click="$refs.orderForm.submit()"
                        class="w-1/2 py-2.5 bg-brand-primary hover:bg-brand-primary/95 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                        Ya, Kirim Sekarang
                    </button>
                </div>
            </div>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>