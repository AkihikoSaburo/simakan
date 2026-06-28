@props([
    'title',
    'description',
    'icon',
])

<div
    class="group flex h-full flex-col bg-white rounded-2xl border border-brand-light shadow-sm p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-brand-primary/30">

    <div
        class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center text-xl transition-colors duration-300 group-hover:bg-brand-primary group-hover:text-white">
        <i class="fa-solid {{ $icon }}"></i>
    </div>

    <h3 class="mt-4 text-lg font-bold text-brand-dark">
        {{ $title }}
    </h3>

    <p class="mt-1 text-sm text-brand-gray leading-relaxed flex-1">
        {{ $description }}
    </p>

    <div class="pt-4 flex items-center gap-2 text-sm font-semibold text-brand-primary">
        Buka
        <i class="fa-solid fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
    </div>

</div>