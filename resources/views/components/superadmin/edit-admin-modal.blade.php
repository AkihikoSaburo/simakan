{{-- Bersihkan x-data lama, biarkan cuman mengelola loading --}}
<x-ui.modal name="edit-admin" size="md" x-data="{ loading: false }">

    <x-slot:header>
        <h2 class="text-xl font-bold text-brand-dark">
            Edit Administrator
        </h2>
    </x-slot:header>

    {{-- 1. Action URL langsung membaca dari payload store --}}
    <form id="edit-admin-form" :action="$store.modal.payload?.action" method="POST" @submit="loading=true">
        @csrf
        @method('PUT')

        {{-- Username --}}
        <div>
            <label for="edit-username" class="block mb-2 text-sm font-semibold text-brand-dark">
                Username
            </label>

            {{-- 2. x-model langsung mengarah ke $store.modal.payload.username --}}
            {{-- Tambahkan x-init untuk memastikan input terisi jika payload berubah --}}
            <input id="edit-username" name="username" type="text" x-model="$store.modal.payload.username"
                autocomplete="username"
                class="w-full rounded-xl border border-brand-light bg-white px-4 py-3 outline-none transition focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 @error('username') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">

            @error('username')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>
        {{-- Password Baru (Optional) --}}
        <div x-data="{ show:false }" class="mt-4">
            <label for="edit-password" class="block mb-2 text-sm font-semibold text-brand-dark">
                Password Baru <span class="text-xs font-normal text-brand-gray">(Kosongkan jika tidak diganti)</span>
            </label>

            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password"
                    class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">

                <button type="button" @click="show=!show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-brand-primary">
                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>

            @error('password')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div x-data="{ show:false }" class="mt-4">
            <label for="edit-password-confirmation" class="block mb-2 text-sm font-semibold text-brand-dark">
                Konfirmasi Password Baru
            </label>

            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                    class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">

                <button type="button" @click="show=!show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-brand-primary">
                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

    </form>

    <x-slot:footer>
        <button type="button" @click="$store.modal.close()" class="inline-flex items-center rounded-xl border border-brand-light px-5 py-2.5 text-sm font-medium text-brand-gray transition hover:bg-brand-light">
            Batal
        </button>

        <button type="submit" form="edit-admin-form" x-data="{ loading: false }"
            @submit.window="$event.target.id === 'edit-admin-form' ? loading = true : null" :disabled="loading"
            class="inline-flex items-center justify-center rounded-xl bg-brand-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-primary/90 disabled:cursor-not-allowed disabled:opacity-60">
            <template x-if="!loading">
                <span>Simpan Perubahan</span>
            </template>
            <template x-if="loading">
                <span>Menyimpan...</span>
            </template>
        </button>
    </x-slot:footer>

</x-ui.modal>