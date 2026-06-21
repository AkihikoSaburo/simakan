@props([
    'title',
    'description',
    'buttonText' => null,
    'buttonRoute' => null,
    'buttonIcon' => 'fa-solid fa-arrow-right',
])

<div
    {{ $attributes->merge([
        'class' => 'bg-gradient-to-r from-brand-primary to-brand-accent rounded-2xl p-6 md:p-8 shadow-xl shadow-brand-light text-brand-snow'
    ]) }}
>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <div class="flex-1">
            <h2 class="text-2xl md:text-3xl font-black tracking-tight">
                {{ $title }}
            </h2>

            <p class="mt-2 text-sm md:text-base text-brand-light/90 max-w-2xl">
                {{ $description }}
            </p>
        </div>

        @if ($buttonText && $buttonRoute)
            <div class="shrink-0">
                <a href="{{ $buttonRoute }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-brand-dark hover:bg-brand-dark/90 transition-all font-bold text-sm whitespace-nowrap">

                    <i class="{{ $buttonIcon }}"></i>

                    {{ $buttonText }}

                </a>
            </div>
        @endif

    </div>
</div>