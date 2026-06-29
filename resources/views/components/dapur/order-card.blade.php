@props([
    'order',
])

<div {{ $attributes->merge(['class' => 'bg-white border border-brand-light rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all']) }}>
    <div class="flex justify-between items-start">
        <div>
            <h3 class="font-black text-lg text-brand-dark">
                {{ $order->bangsal->nama_bangsal }}
            </h3>

<p class="text-xs text-brand-gray">
    Dikirim {{ $order->created_at->format('H:i') }} {{ $order->created_at->isoFormat('z') ?? $order->created_at->format('T') }}
</p>
        </div>

        <div class="text-2xl">
            🏥
        </div>
    </div>

    <div class="mt-5 space-y-2 text-sm">
        <div class="flex justify-between">
            <span>Total Pasien</span>
            <strong>{{ $order->orderDetails->count() }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Nasi</span>
            <strong>{{ $order->nasi_count }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Bubur</span>
            <strong>{{ $order->bubur_count }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Masakan Cair / Susu</span>
            <strong>{{ $order->makanan_cair_count }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Bubur Saring</span>
            <strong>{{ $order->bs_count }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Sonde</span>
            <strong>{{ $order->sonde_count }}</strong>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('dapur.orders.show', $order) }}"
            class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 rounded-xl bg-brand-primary text-brand-snow font-semibold hover:bg-brand-primary/90 transition-all">
            <i class="fa-solid fa-eye"></i>
            Lihat Detail
        </a>
    </div>
</div>
