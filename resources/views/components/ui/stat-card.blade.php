@props([
    'title',
    'value',
    'valueClass' => 'text-3xl font-black text-brand-primary',
])

<div
    {{ $attributes->merge([
        'class' => 'bg-white border border-brand-light rounded-xl p-5 shadow-sm'
    ]) }}
>

    <p class="text-xs uppercase font-bold text-brand-gray">
        {{ $title }}
    </p>

    <h3 class="mt-2 {{ $valueClass }}">
        {{ $value }}
    </h3>

</div>