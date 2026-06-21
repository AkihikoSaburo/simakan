@props([
    'stats' => [],
])

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

    @foreach ($stats as $stat)

        <x-ui.stat-card
            :title="$stat['title']"
            :value="$stat['value']"
            :value-class="$stat['valueClass'] ?? 'text-3xl font-black text-brand-primary'"
        />

    @endforeach

</div>