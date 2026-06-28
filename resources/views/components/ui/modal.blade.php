@props([
    'name',
    'size' => 'md',
])

@php
    $maxWidth = match ($size) {
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };
@endphp

<div
    x-data="{
        get open() {
            return $store.modal.isOpen('{{ $name }}')
        }
    }"

    x-show="open"
    x-cloak

    @keydown.escape.window="$store.modal.close()"

    x-effect="
        document.body.classList.toggle('overflow-hidden', open)
    "

    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>

    {{-- Overlay --}}
    <div
        x-show="open"
        x-transition.opacity.duration.200ms
        @click="$store.modal.close()"
        class="absolute inset-0 bg-black/50"
    ></div>

    {{-- Modal --}}
    <div
        x-show="open"
        @click.stop

        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"

        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"

        class="relative w-full {{ $maxWidth }} overflow-hidden rounded-3xl bg-white shadow-2xl"
    >

        @isset($header)
            <div class="flex items-center justify-between border-b border-brand-light px-6 py-5">

                {{ $header }}

                <button
                    type="button"
                    @click="$store.modal.close()"
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-brand-gray transition hover:bg-brand-light hover:text-brand-dark">

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>
        @endisset

        <div class="p-6">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex justify-end gap-3 border-t border-brand-light bg-brand-snow px-6 py-4">
                {{ $footer }}
            </div>
        @endisset

    </div>

</div>