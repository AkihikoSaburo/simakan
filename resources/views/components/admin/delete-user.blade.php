<x-ui.modal name="delete-user" size="sm" x-data="{ loading: false }">

    <x-slot:header>
        <h2 class="text-xl font-bold text-rose-600 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Konfirmasi Hapus
        </h2>
    </x-slot:header>

    {{-- Action URL diikat otomatis ke payload action destroy --}}
    <form id="delete-user-form" :action="$store.modal.payload?.action" method="POST" @submit="loading = true">
        @csrf
        @method('DELETE')

        <div class="text-center sm:text-left">
            <p class="text-sm text-brand-dark">
                Apakah Anda yakin ingin menghapus akun pengguna dengan username 
                <span class="font-bold text-brand-primary" x-text="$store.modal.payload?.username"></span>?
            </p>
            <p class="mt-2 text-xs text-brand-gray">
                Tindakan ini tidak dapat dibatalkan dan seluruh hak akses akun ini akan dicabut segera.
            </p>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="$store.modal.close()" :disabled="loading"
            class="inline-flex items-center rounded-xl border border-brand-light px-5 py-2.5 text-sm font-medium text-brand-gray transition hover:bg-brand-light disabled:opacity-50">
            Batal
        </button>

        {{-- Mengikat status disabled & loading ke state form di atas --}}
        <button type="submit" form="delete-admin-form" 
            x-data="{ loading: false }"
            @submit.window="$event.target.id === 'delete-admin-form' ? loading = true : null"
            :disabled="loading"
            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-60">

            <template x-if="!loading">
                <span>Ya, Hapus Akun</span>
            </template>

            <template x-if="loading">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin"></i> Menghapus...
                </span>
            </template>
        </button>
    </x-slot:footer>

</x-ui.modal>