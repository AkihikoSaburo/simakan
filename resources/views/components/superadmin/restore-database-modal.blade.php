<x-ui.modal name="restore-database" size="md" x-data="{ loading: false }">

    <x-slot:header>
        <h2 class="text-xl font-bold text-rose-600 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Restore Database Sistem
        </h2>
    </x-slot:header>

    {{-- Ingat tambahkan enctype="multipart/form-data" karena ada proses upload file --}}
    {{-- x-data tambahan di form untuk verifikasi teks secara real-time --}}
    <form id="restore-database-form" action="{{ route('superadmin.database.restore') }}" method="POST" enctype="multipart/form-data"
        x-data="{ confirmationText: '' }"
        @submit="loading=true">
        @csrf

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 text-sm text-amber-800">
            <strong>Peringatan Keamanan Kerja!</strong> Proses restore akan **menimpa dan menghapus** data database saat ini dengan data dari file backup yang Anda pilih.
        </div>

        {{-- Input File SQL --}}
        <div>
            <label for="backup_file" class="block mb-2 text-sm font-semibold text-brand-dark">
                Pilih File Backup (.sql)
            </label>
            <input id="backup_file" name="backup_file" type="file" accept=".sql" required
                class="w-full text-sm text-brand-gray file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-brand-primary/10 file:text-brand-primary hover:file:bg-brand-primary/20">
            @error('backup_file')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Langkah Verifikasi Keamanan Kedua --}}
        <div class="mt-5 border-t border-brand-light pt-4">
            <label for="confirmation" class="block mb-1 text-sm font-semibold text-brand-dark">
                Verifikasi Tindakan
            </label>
            <p class="text-xs text-brand-gray mb-2">
                Ketik kalimat <span class="font-bold text-brand-dark select-all">PULIHKAN DATABASE</span> di bawah untuk mengaktifkan tombol konfirmasi:
            </p>
            
            <input id="confirmation" name="confirmation" type="text" autocomplete="off"
                x-model="confirmationText"
                placeholder="Tulis kalimat verifikasi di sini..."
                class="w-full rounded-xl border border-brand-light bg-white px-4 py-3 outline-none transition focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
        </div>

    </form>

    <x-slot:footer>
        <button type="button" @click="$store.modal.close()"
            class="inline-flex items-center rounded-xl border border-brand-light px-5 py-2.5 text-sm font-medium text-brand-gray transition hover:bg-brand-light">
            Batal
        </button>

        {{-- Tombol dikunci (disabled) secara real-time lewat Alpine menggunakan :disabled jika teks belum sesuai --}}
        <button type="submit" form="restore-database-form" 
            x-data="{ loading: false, confirmInput: '' }"
            @submit.window="$event.target.id === 'restore-database-form' ? loading = true : null"
            @input.window="if($event.target.id === 'confirmation') confirmInput = $event.target.value"
            :disabled="loading || confirmInput !== 'PULIHKAN DATABASE'"
            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-40">

            <template x-if="!loading">
                <span>Mulai Restore</span>
            </template>

            <template x-if="loading">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin"></i> Memulihkan Sistem...
                </span>
            </template>
        </button>
    </x-slot:footer>

</x-ui.modal>