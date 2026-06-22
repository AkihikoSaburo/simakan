<tr class="hover:bg-brand-light/5 transition-all duration-300 ease-out align-top">
    <!-- INDEX -->
    <td class="py-3 px-4 text-center font-bold text-brand-gray" x-text="index + 1"></td>

    <!-- NAMA PASIEN (Pemicu Autosuggestion) -->
    <td class="py-3 px-3 relative" x-data="{ 
        suggestions: [], 
        showSuggestions: false,
        timeout: null,
        fetchPatients() {
            // Menggunakan debounce agar tidak membombardir database setiap ketikan huruf
            clearTimeout(this.timeout);
            if (pasien.nama_pasien.length < 3) { 
                this.suggestions = []; 
                this.showSuggestions = false;
                return; 
            }
            this.timeout = setTimeout(async () => {
                try {
                    // Kirim query berdasarkan nama ke backend
                    let response = await fetch(`/bangsal/cari-pasien?nama=${encodeURIComponent(pasien.nama_pasien)}`);
                    this.suggestions = await response.json();
                    this.showSuggestions = this.suggestions.length > 0;
                } catch (error) {
                    console.error('Gagal mengambil data pasien:', error);
                }
            }, 300); // Debounce 300ms
        },
        pilihPasien(p) {
            pasien.nama_pasien = p.nama;
            pasien.no_rm = p.no_rm;
            pasien.kamar_kelas = p.kamar;
            this.suggestions = [];
            this.showSuggestions = false;
        }
    }" @click.away="showSuggestions = false">

        <input type="text" :name="'pasiens['+index+'][nama_pasien]'" x-model="pasien.nama_pasien"
            @input="fetchPatients()" @focus="if(suggestions.length > 0) showSuggestions = true" required
            placeholder="Nama Lengkap" class="form-input w-full">

        <!-- Dropdown Hasil Pencarian Nama -->
        <div x-show="showSuggestions" x-cloak x-transition:enter="transition-all ease-out duration-300"
            x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-48"
            x-transition:leave="transition-all ease-in duration-200" x-transition:leave-start="opacity-100 max-h-48"
            x-transition:leave-end="opacity-0 max-h-0"
            class="mt-1 bg-white border border-brand-light rounded-xl shadow-xl overflow-hidden divide-y divide-brand-light max-h-48 overflow-y-auto">

            <template x-for="p in suggestions" :key="p.no_rm">
                <button type="button" @click="pilihPasien(p)"
                    class="w-full text-left px-4 py-2.5 hover:bg-brand-light/40 transition-colors flex flex-col gap-0.5">
                    <span class="text-xs font-bold text-brand-primary" x-text="p.nama"></span>
                    <span class="text-xs text-brand-gray">
                        No. RM: <span class="font-mono font-semibold text-brand-dark" x-text="p.no_rm"></span>
                        (<span x-text="p.kamar"></span>)
                    </span>
                </button>
            </template>
        </div>
    </td>

    <!-- NO. RM (Terisi Otomatis) -->
    <td class="py-3 px-3">
        <input type="text" :name="'pasiens['+index+'][no_rm]'" x-model="pasien.no_rm"
            @input="pasien.no_rm = pasien.no_rm.replace(/[^0-9]/g, '');" required placeholder="00-00-00"
            class="form-input font-mono w-full">
    </td>

    <!-- KAMAR / KELAS (Terisi Otomatis) -->
    <td class="py-3 px-3">
        <input type="text" :name="'pasiens['+index+'][kamar_kelas]'" x-model="pasien.kamar_kelas" required
            placeholder="Kmr 3 / Klst II" class="form-input">
    </td>

    <!-- BENTUK MAKANAN -->
    <td class="py-3 px-3">
        <button type="button" @click="activePasienIndex = index"
            class="form-input flex items-center justify-between w-full text-left">
            <span class="truncate"
                :class="pasien.bentuk_makanan.length ? 'text-brand-dark font-semibold' : 'text-brand-gray'"
                x-text="pasien.bentuk_makanan.length ? pasien.bentuk_makanan.join(', ') : 'Pilih Makanan...'">
            </span>
            <i class="fa-solid fa-list-check text-xs text-brand-gray ml-1"></i>
        </button>

        <template x-for="makanan in pasien.bentuk_makanan">
            <input type="hidden" :name="'pasiens['+index+'][bentuk_makanan][]'" :value="makanan">
        </template>
    </td>

    <!-- DIET -->
    <td class="py-3 px-3">
        <input type="text" :name="'pasiens['+index+'][diet]'" x-model="pasien.diet"
            placeholder="Contoh: RG (Rendah Garam), DM" class="form-input">
    </td>

    <!-- KETERANGAN -->
    <td class="py-3 px-3">
        <input type="text" :name="'pasiens['+index+'][keterangan]'" x-model="pasien.keterangan"
            placeholder="Contoh: Tanpa Telur, Alergi" class="form-input">
    </td>

    <!-- HAPUS BARIS -->
    <td class="py-3 px-4 text-center">
        <button type="button" @click="pasiens.splice(index, 1)"
            class="text-rose-500 hover:text-rose-700 transition-colors text-sm">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    </td>
</tr>