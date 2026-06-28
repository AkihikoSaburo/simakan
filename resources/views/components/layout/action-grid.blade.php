@props([
    'actions' => [],
])

@php
    $count = count($actions);

    $gridClass = match (true) {
        $count <= 1 => 'grid-cols-1',
        $count == 2 => 'grid-cols-1 md:grid-cols-2',
        $count == 3 => 'grid-cols-1 md:grid-cols-3',
        $count == 4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    };
@endphp

<div class="grid gap-4 {{ $gridClass }}">
    @foreach ($actions as $action)

        @if(isset($action['modal']))
            <button type="button" @click="$store.modal.open('{{ $action['modal'] }}')" class="block w-full text-left">
                <x-ui.action-card
                    :title="$action['title']"
                    :description="$action['description']"
                    :icon="$action['icon']"
                    :color="$action['color'] ?? 'primary'"
                />
            </button>
        @else
            <a href="{{ $action['href'] }}" class="block">
                <x-ui.action-card
                    :title="$action['title']"
                    :description="$action['description']"
                    :icon="$action['icon']"
                    :color="$action['color'] ?? 'primary'"
                />
            </a>
        @endif

    @endforeach
</div>