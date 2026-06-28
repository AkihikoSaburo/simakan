@props([
    'orders',
    'bangsalName' => null, // Tambahkan properti opsional ini
])

<div {{ $attributes->merge(['class' => 'bg-brand-snow rounded-xl border border-brand-light shadow-sm overflow-hidden']) }}>
   <div class="p-5 border-b border-brand-light flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-brand-dark text-lg">Riwayat Pengiriman Form</h3>
            {{-- Modifikasi baris p di bawah ini agar fleksibel --}}
            <p class="text-brand-gray text-xs mt-0.5">
                Menampilkan seluruh data log permintaan makanan dari 
                <span class="font-semibold text-brand-primary">
                    {{ $bangsalName ?? (auth()->user()->bangsal->nama_bangsal ?? 'Bangsal') }}
                </span>
            </p>
        </div>

        {{-- BUNGKUS DENGAN FORM GET METHOD --}}
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3">
            <input type="date" 
                name="date" 
                value="{{ request('date') }}"
                class="bg-brand-snow border border-brand-light text-brand-dark rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
            
            {{-- Tombol ganti menjadi type="submit" --}}
            <button type="submit"
                class="bg-brand-primary text-brand-snow font-semibold text-sm px-4 py-1.5 rounded-lg hover:bg-brand-primary/95 transition-colors">
                <i class="fa-solid fa-filter mr-1.5"></i>Filter
            </button>

            {{-- Tombol Reset (Opsional: Muncul hanya saat filter sedang aktif) --}}
            @if(request('date'))
                <a href="{{ url()->current() }}" 
                    class="border border-brand-light bg-white text-brand-gray hover:text-brand-dark font-medium text-sm px-3 py-1.5 rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr 
                    class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                    <th class="py-3 px-5">ID Form</th>
                    <th class="py-3 px-5">Tanggal Pengiriman</th>
                    <th class="py-3 px-5">Total Pasien</th>
                    <th class="py-3 px-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-brand-light text-sm">

                @forelse($orders as $order)
                    <tr class="hover:bg-brand-light/20 transition-colors">
                        <td class="py-4 px-5 font-mono text-xs font-semibold text-brand-slate">
                            #FM-{{ $order->tanggal_pesanan->format('Ymd') }}-{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="py-4 px-5 font-medium text-brand-dark">
                            {{ $order->tanggal_pesanan->translatedFormat('d F Y') }}
                        </td>

                        <td class="py-4 px-5 font-bold text-brand-dark">
                            {{ $order->orderDetails->count() }} Pasien
                        </td>

                        <td class="py-4 px-5 text-center">
                            <a href="{{ request()->is('admin/*') ? route('admin.arsip.orders.show', $order) : route('bangsal.orders.show', $order) }}"
                            class="text-brand-primary hover:text-brand-accent font-bold text-xs inline-flex items-center gap-1 bg-brand-light/60 hover:bg-brand-light px-3 py-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i> Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-brand-gray">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-regular fa-folder-open text-4xl"></i>
                                <div>
                                    <p class="font-semibold">
                                        Belum Ada Riwayat Pengiriman
                                    </p>
                                    <p class="text-xs mt-1">
                                        Form permintaan makanan yang telah dikirim akan muncul di sini.
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-brand-light flex items-center justify-between text-xs text-brand-gray">
        <span>Menampilkan {{ $orders->count() }} dari {{ $orders->total() }} form terbaru</span>
        <div class="inline-flex shadow-sm rounded-lg overflow-hidden border border-brand-light">
            {{-- Tombol Previous --}}
            @if ($orders->onFirstPage())
                <button class="bg-brand-snow px-3 py-1.5 text-brand-gray/50 cursor-not-allowed font-medium" disabled>Previous</button>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="bg-brand-snow px-3 py-1.5 hover:bg-brand-light font-medium text-brand-dark">Previous</a>
            @endif

            {{-- Tombol Next --}}
            @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="bg-brand-snow px-3 py-1.5 hover:bg-brand-light font-medium text-brand-dark">Next</a>
            @else
            <button class="bg-brand-snow px-3 py-1.5 text-brand-gray/50 cursor-not-allowed font-medium" disabled>Next</button>
            @endif
        </div>
    </div>
</div>
