@props([
    'orders',
])

@if($orders->isEmpty())
    <div class="bg-white border border-brand-light rounded-2xl p-12 text-center shadow-sm">
        <div class="text-6xl mb-4">
            📭
        </div>

        <h3 class="text-xl font-bold text-brand-dark">
            Belum Ada Permintaan Hari Ini
        </h3>

        <p class="text-brand-gray mt-2 max-w-md mx-auto">
            Hingga saat ini belum ada bangsal yang mengirim formulir permintaan makanan pasien.
            Data akan muncul secara otomatis setelah bangsal melakukan pengiriman.
        </p>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6']) }}>
        @foreach($orders as $order)
            <x-dapur.order-card :order="$order" />
        @endforeach
    </div>
@endif
