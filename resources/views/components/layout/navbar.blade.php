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
                SIMAKAN RSUD
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

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf

            <button
                type="submit"
                class="text-brand-gray hover:text-rose-600 p-2 rounded-lg transition-colors"
                title="Keluar"
            >
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </button>
        </form>
    </div>
</nav>