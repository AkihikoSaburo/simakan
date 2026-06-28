@props([
    'title',
    'description',
    'icon',
    'color' => 'primary', 
])

@php
    // Mapping warna untuk SELURUH BODY CARD dan elemen di dalamnya
    $colorClasses = match ($color) {
        'success' => [
            'card-bg' => 'bg-emerald-50/50 border-emerald-100 hover:border-emerald-300 hover:bg-emerald-50',
            'icon-box' => 'bg-emerald-500 text-white',
            'title' => 'text-emerald-900',
            'desc' => 'text-emerald-700/80',
            'link' => 'text-emerald-600',
        ],
        'warning' => [
            'card-bg' => 'bg-amber-50/50 border-amber-100 hover:border-amber-300 hover:bg-amber-50',
            'icon-box' => 'bg-amber-500 text-white',
            'title' => 'text-amber-900',
            'desc' => 'text-amber-700/80',
            'link' => 'text-amber-600',
        ],
        'danger' => [
            'card-bg' => 'bg-rose-50/50 border-rose-100 hover:border-rose-300 hover:bg-rose-50',
            'icon-box' => 'bg-rose-500 text-white',
            'title' => 'text-rose-900',
            'desc' => 'text-rose-700/80',
            'link' => 'text-rose-600',
        ],
        'info' => [
            'card-bg' => 'bg-sky-50/50 border-sky-100 hover:border-sky-300 hover:bg-sky-50',
            'icon-box' => 'bg-sky-500 text-white',
            'title' => 'text-sky-900',
            'desc' => 'text-sky-700/80',
            'link' => 'text-sky-600',
        ],
        // Default (Putih dengan aksen warna brand utama kamu)
        default => [
            'card-bg' => 'bg-white border-brand-light hover:border-brand-primary/30',
            'icon-box' => 'bg-brand-primary/10 text-brand-primary group-hover:bg-brand-primary group-hover:text-white',
            'title' => 'text-brand-dark',
            'desc' => 'text-brand-gray',
            'link' => 'text-brand-primary',
        ],
    };
@endphp

<div
    class="group flex h-full flex-col rounded-2xl border shadow-sm p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $colorClasses['card-bg'] }}">

    <div
        class="w-12 h-12 rounded-xl flex items-center justify-center text-xl transition-colors duration-300 {{ $colorClasses['icon-box'] }}">
        <i class="fa-solid {{ $icon }}"></i>
    </div>

    <h3 class="mt-4 text-lg font-bold {{ $colorClasses['title'] }}">
        {{ $title }}
    </h3>

    <p class="mt-1 text-sm leading-relaxed flex-1 {{ $colorClasses['desc'] }}">
        {{ $description }}
    </p>

    <div class="pt-4 flex items-center gap-2 text-sm font-semibold {{ $colorClasses['link'] }}">
        Buka
        <i class="fa-solid fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
    </div>

</div>