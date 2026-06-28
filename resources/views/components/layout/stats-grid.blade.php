@props([
    'stats' => [],
])

@php
    $count = count($stats);

    $gridClass = match (true) {
        $count <= 1 => 'grid-cols-1',
        $count == 2 => 'grid-cols-1 md:grid-cols-2',
        $count == 3 => 'grid-cols-1 md:grid-cols-3',
        $count == 4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5',
    };
@endphp

<div class="grid gap-4 {{ $gridClass }}">
    @foreach ($stats as $stat)
        <x-ui.stat-card
            :title="$stat['title']"
            :value="$stat['value']"
            :value-class="$stat['valueClass'] ?? 'text-3xl font-black text-brand-primary'"
        />
    @endforeach
</div>