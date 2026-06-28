@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - SIMAKAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar 
    title="Dashboard Dapur" 
    icon="fa-utensils" 
    />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card
            title="Riwayat Laporan Harian 🕒"
            description="Pantau dan cetak rekapitulasi data permintaan makanan pasien dari semua bangsal berdasarkan hari."
            :button-text="'Dashboard Dapur'"
            :button-route="route('dapur.dashboard')"
            :button-icon="'fa-solid fa-chart-line'"
        />

        @if($paginatedDates->isEmpty())
            <div class="bg-white border border-brand-light rounded-2xl p-12 text-center shadow-sm">
                <div class="text-6xl mb-4">
                    📅
                </div>
                <h3 class="text-xl font-bold text-brand-dark">
                    Belum Ada Riwayat Pesanan
                </h3>
                <p class="text-brand-gray mt-2 max-w-md mx-auto">
                    Data riwayat pesanan akan muncul secara otomatis setelah bangsal melakukan pengiriman permintaan makanan.
                </p>
            </div>
        @else
            <!-- Grid Riwayat Harian -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($paginatedDates as $dateItem)
                    @php
                        $dateString = $dateItem->tanggal_pesanan->format('Y-m-d');
                        $dayOrders = $groupedOrders->get($dateString, collect());
                        
                        // Calculate sums
                        $totalPasien = $dayOrders->sum(fn($order) => $order->orderDetails->count());
                        $totalNasi = $dayOrders->sum(fn($o) => $o->nasi_count);
                        $totalBubur = $dayOrders->sum(fn($o) => $o->bubur_count);
                        $totalMakananCair = $dayOrders->sum(fn($o) => $o->makanan_cair_count);
                        $totalBs = $dayOrders->sum(fn($o) => $o->bs_count);
                        $totalSonde = $dayOrders->sum(fn($o) => $o->sonde_count);
                    @endphp

                    <!-- Card Harian x-data untuk kontrol Modal -->
                    <div x-data="{ openModal: false }" class="bg-white border border-brand-light rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="space-y-1">
                                    <h3 class="font-black text-base text-brand-dark leading-tight pt-1">
                                        {{ $dateItem->tanggal_pesanan->locale('id')->translatedFormat('l, d F Y') }}
                                    </h3>
                                </div>
                                <div class="text-2xl text-brand-primary">
                                    📋
                                </div>
                            </div>

                            <div class="border-t border-brand-light my-3"></div>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-brand-gray font-medium">Total Pasien</span>
                                    <strong class="text-brand-dark">{{ $totalPasien }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-gray">Nasi</span>
                                    <strong class="text-brand-dark">{{ $totalNasi }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-gray">Bubur</span>
                                    <strong class="text-brand-dark">{{ $totalBubur }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-gray">Masakan Cair / Susu</span>
                                    <strong class="text-brand-dark">{{ $totalMakananCair }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-gray">Bubur Saring</span>
                                    <strong class="text-brand-dark">{{ $totalBs }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-gray">Sonde</span>
                                    <strong class="text-brand-dark">{{ $totalSonde }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="grid grid-cols-2 gap-3 mt-6">
                            <button @click="openModal = true" class="inline-flex justify-center items-center gap-1.5 px-3 py-2.5 rounded-xl bg-brand-primary text-brand-snow font-bold text-xs hover:bg-brand-primary/95 transition-all cursor-pointer shadow-md">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Detail
                            </button>
                            <a href="{{ route('dapur.history.pdf', $dateString) }}" target="_blank" class="inline-flex justify-center items-center gap-1.5 px-3 py-2.5 rounded-xl bg-rose-600 text-brand-snow font-bold text-xs hover:bg-rose-700 transition-all cursor-pointer shadow-md">
                                <i class="fa-solid fa-file-pdf"></i>
                                Export PDF
                            </a>
                        </div>

                        <!-- Modal Detail Pesanan Harian -->
                        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" x-cloak>
                            <div @click.away="openModal = false" class="bg-white rounded-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl transition-all" x-transition>
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between px-6 py-4 border-b border-brand-light bg-brand-light/10">
                                    <div>
                                        <span class="text-[10px] font-bold text-brand-primary uppercase tracking-wider">Detail Pesanan Harian</span>
                                        <h3 class="font-black text-lg text-brand-dark mt-0.5">
                                            Hari {{ $dateItem->tanggal_pesanan->locale('id')->translatedFormat('l, d F Y') }}
                                        </h3>
                                    </div>
                                    <button @click="openModal = false" class="text-brand-gray hover:text-brand-dark p-1.5 rounded-lg hover:bg-brand-light/50 transition-colors">
                                        <i class="fa-solid fa-xmark text-xl"></i>
                                    </button>
                                </div>

                                <!-- Modal Content (Scrollable) -->
                                <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-brand-snow/50">
                                    @forelse($dayOrders as $order)
                                        <div class="bg-white border border-brand-light rounded-xl p-5 shadow-xs">
                                            <!-- Sub-header Ward -->
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 pb-3 mb-4 border-b border-brand-light">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2.5 py-1 bg-brand-primary text-brand-snow text-xs font-bold rounded-lg flex items-center gap-1.5">
                                                        <i class="fa-solid fa-house-medical"></i>
                                                        {{ $order->bangsal->nama_bangsal }}
                                                    </span>
                                                    <span class="text-xs text-brand-gray font-medium">
                                                        Form ID: #{{ $order->id }}
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-brand-slate">
                                                    <span>
                                                        <i class="fa-solid fa-clock text-brand-primary mr-1"></i>
                                                        Dikirim {{ $order->created_at->format('H:i') }} WITA
                                                    </span>
                                                    <span class="w-1.5 h-1.5 bg-brand-light rounded-full hidden sm:inline"></span>
                                                    <span>
                                                        <i class="fa-solid fa-user text-brand-primary mr-1"></i>
                                                        Petugas: {{ $order->creator->username ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Patient details table for this ward -->
                                            <div class="overflow-x-auto rounded-lg border border-brand-light">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold uppercase tracking-wider">
                                                            <th class="py-2.5 px-3 w-10 text-center">No</th>
                                                            <th class="py-2.5 px-3 min-w-[150px]">Nama Pasien</th>
                                                            <th class="py-2.5 px-3 min-w-[100px]">No. RM</th>
                                                            <th class="py-2.5 px-3 min-w-[100px]">Kamar / Kelas</th>
                                                            <th class="py-2.5 px-3 min-w-[180px]">Bentuk Makanan</th>
                                                            <th class="py-2.5 px-3 min-w-[120px]">Jenis Diet</th>
                                                            <th class="py-2.5 px-3 min-w-[150px]">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-brand-light text-brand-dark">
                                                        @php $idx = 1; @endphp
                                                        @forelse($order->orderDetails as $detail)
                                                            <tr class="hover:bg-brand-light/5 transition-colors align-top bg-white">
                                                                <td class="py-2.5 px-3 text-center font-bold text-brand-gray">{{ $idx++ }}</td>
                                                                <td class="py-2.5 px-3 font-semibold text-brand-dark">{{ $detail->patient->nama ?? 'Tanpa Nama' }}</td>
                                                                <td class="py-2.5 px-3 font-mono text-brand-slate">{{ $detail->patient->no_rm ?? '-' }}</td>
                                                                <td class="py-2.5 px-3 text-brand-slate">{{ $detail->patient->kamar ?? '-' }}</td>
                                                                <td class="py-2.5 px-3">
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @php
                                                                            $makananArr = [];
                                                                            if($detail->nasi) $makananArr[] = 'Nasi';
                                                                            if($detail->bubur) $makananArr[] = 'Bubur';
                                                                            if($detail->makanan_cair) $makananArr[] = 'Msk. Cair / Susu';
                                                                            if($detail->bs) $makananArr[] = 'Bubur Saring';
                                                                            if($detail->sonde) $makananArr[] = 'Sonde';
                                                                        @endphp
                                                                        @forelse($makananArr as $mItem)
                                                                            <span class="inline-flex items-center bg-brand-light/40 text-brand-primary text-[10px] font-bold px-1.5 py-0.5 rounded">
                                                                                {{ $mItem }}
                                                                            </span>
                                                                        @empty
                                                                            <span class="text-brand-gray text-[10px] italic">Belum memilih</span>
                                                                        @endforelse
                                                                    </div>
                                                                </td>
                                                                <td class="py-2.5 px-3 text-brand-slate font-medium">{{ $detail->diet_pasien ?? '-' }}</td>
                                                                <td class="py-2.5 px-3 text-[11px] text-brand-gray italic">{{ $detail->keterangan ?? 'Tidak ada keterangan' }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="py-6 text-center text-brand-gray italic">
                                                                    Belum ada data pasien untuk pesanan ini.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-12 text-brand-gray">
                                            Tidak ada rincian data order untuk tanggal ini.
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Modal Footer -->
                                <div class="px-6 py-4 border-t border-brand-light flex justify-end bg-brand-light/5">
                                    <button @click="openModal = false" class="px-4 py-2 bg-brand-slate hover:bg-brand-slate/90 text-brand-snow rounded-xl font-bold text-xs transition-colors cursor-pointer">
                                        Tutup Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination links -->
            <div class="mt-8 flex justify-center">
                {{ $paginatedDates->links() }}
            </div>
        @endif

    </main>

    <x-layout.footer />

</body>

</html>