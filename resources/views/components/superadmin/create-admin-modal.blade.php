{{-- 1. Pindahkan x-data ke sini agar tombol di footer bisa membaca status 'loading' --}}
<x-ui.modal name="create-admin" size="md" x-data="{
        loading: false,
        showPassword: false,
        showConfirmation: false
    }">

    <x-slot:header>
        <h2 class="text-xl font-bold text-brand-dark">
            Tambah Administrator
        </h2>
    </x-slot:header>

    {{-- Hapus x-data dari tag form ini --}}
    <form id="create-admin-form" action="{{ route('superadmin.administrators.store') }}" method="POST"
        @submit="loading=true">
        @csrf

        {{-- Username --}}
        <div>
            <label for="username" class="block mb-2 text-sm font-semibold text-brand-dark">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username"
                class="w-full rounded-xl border border-brand-light bg-white px-4 py-3 outline-none transition focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 @error('username') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">
            @error('username')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show:false }" class="mt-4">
            <label for="password" class="block mb-2 text-sm font-semibold text-brand-dark">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password"
                    class="w-full rounded-xl border border-brand-light px-4 py-3 pr-12">
                <button type="button" @click="show=!show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-brand-primary">
                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div x-data="{ show:false }" class="mt-4">
            <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-brand-dark">Konfirmasi
                Password</label>
            <div class="relative">
                {{-- 2. Perbaikan: Ubah name menjadi password_confirmation --}}
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
        <button type="button" @click="$store.modal.close()"
            class="inline-flex items-center rounded-xl border border-brand-light px-5 py-2.5 text-sm font-medium text-brand-gray transition hover:bg-brand-light">
            Batal
        </button>

        {{-- Tombol ini mengelola x-data 'loading' sendiri --}}
        <button type="submit" form="create-admin-form" x-data="{ loading: false }"
            @submit.window="$event.target.id === 'create-admin-form' ? loading = true : null" :disabled="loading"
            class="inline-flex items-center justify-center rounded-xl bg-brand-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-primary/90 disabled:cursor-not-allowed disabled:opacity-60">

            <template x-if="!loading">
                <span>Simpan</span>
            </template>

            <template x-if="loading">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...
                </span>
            </template>
        </button>
    </x-slot:footer>

</x-ui.modal>