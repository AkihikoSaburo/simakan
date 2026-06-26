@php
    use Illuminate\Support\Str;

    $user = auth()->user();
@endphp

@props([
    'title',
    'icon' => 'fa-user-nurse',
])

<nav class="sticky top-0 z-50 bg-brand-snow border-b border-brand-light px-6 py-4 flex items-center justify-between shadow-sm">
    <div class="flex items-center space-x-3">
        {{ $slot }}

        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-primary">
                SIMAKAN {{ $nama_rumah_sakit ?? 'RSUD Andi Makkasau' }}
            </span>

            <h1 class="text-lg font-extrabold text-brand-dark tracking-tight -mt-1">
                {{ $title }}
            </h1>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <div class="text-right hidden md:block">
            <span class="badge-brand">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>

                @if($user->role && strtolower($user->role) !== strtolower($user->username))
                    {{ Str::title($user->role) }}
                @endif

                {{ Str::title($user->username) }}
            </span>
        </div>

        <div class="h-10 w-10 rounded-xl bg-brand-snow border border-brand-light flex items-center justify-center text-brand-gray shadow-inner">
            <i class="fa-solid {{ $icon }} text-lg"></i>
        </div>

        <div x-data="{ showLogoutModal: false }" class="inline">
            <!-- Trigger Button -->
            <button
                type="button"
                @click="showLogoutModal = true"
                class="text-brand-gray hover:text-rose-600 p-2 rounded-lg transition-colors cursor-pointer"
                title="Keluar"
            >
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </button>

            <!-- Custom Logout Modal -->
            <div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" 
                     x-show="showLogoutModal"
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     @click="showLogoutModal = false">
                </div>

                <!-- Modal Dialog -->
                <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-xl border border-brand-light overflow-hidden p-6 text-center"
                     x-show="showLogoutModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>

                    <h3 class="text-lg font-extrabold text-brand-dark">Konfirmasi Keluar</h3>
                    <p class="text-sm text-brand-gray mt-2">Apakah Anda yakin ingin keluar dari akun Anda?</p>

                    <div class="mt-6 flex items-center justify-center gap-3">
                        <button type="button" @click="showLogoutModal = false"
                                class="px-5 py-2.5 border border-brand-light text-brand-slate font-bold rounded-xl hover:bg-brand-light/30 transition-colors text-xs cursor-pointer">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 hover:opacity-95 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 active:scale-95 transition-all text-xs cursor-pointer">
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>