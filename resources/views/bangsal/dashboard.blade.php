<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar title="Digitalisasi Form Makanan" role="Bangsal" :username="auth()->user()->username"
        icon="fa-user-nurse" />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card title="Selamat Datang di Portal Bangsal 👋"
            description="Silakan input lembar permintaan makanan pasien Anda hari ini." />
        
        <x-layout.action-grid :actions="[
        [
            'title' => 'Buat Permintaan Baru',
            'description' => 'Buat lembar permintaan makanan untuk pasien.',
            'icon' => 'fa-user-plus',
            'color' => 'success',
            'href' => route('bangsal.orders.create'),
        ],
        [
            'title' => 'Riwayat Pengiriman Form',
            'description' => 'Lihat riwayat pengiriman form makanan.',
            'icon' => 'fa-clock-rotate-left',
            'color' => 'info',
            'href' => route('bangsal.riwayat'),
        ]
    ]" />

        <x-layout.stats-grid :stats="[
        [
            'title' => 'Total Nasi',
            'value' => $todayOrders->sum('nasi_count'),
        ],
        [
            'title' => 'Total Bubur',
            'value' => $todayOrders->sum('bubur_count'),
        ],
        [
            'title' => 'Total Masakan Cair / Susu',
            'value' => $todayOrders->sum('makanan_cair_count'),
        ],
        [
            'title' => 'Total Bubur Saring',
            'value' => $todayOrders->sum('bs_count'),
        ],
        [
            'title' => 'Total Sonde',
            'value' => $todayOrders->sum('sonde_count'),
        ],
    ]" />


        <div class="bg-brand-snow rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-brand-light flex items-center justify-between bg-brand-light/10">
                <div>
                    <h3 class="font-bold text-brand-dark text-lg">Daftar Permintaan Pasien</h3>
                    <p class="text-brand-gray text-xs mt-0.5">Berikut adalah daftar pesanan yang telah dibuat
                        hari ini</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="tabelPasien">
                    <thead>
                        <tr
                            class="bg-brand-light/40 border-b border-brand-light text-brand-gray font-bold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 min-w-[200px]">Nama Pasien</th>
                            <th class="py-3.5 px-4 min-w-[130px]">No. RM</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Kamar / Kelas</th>
                            <th class="py-3.5 px-4 min-w-[160px]">Bentuk Makanan</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Jenis Diet</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Keterangan Tambahan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm transition-all duration-300 ease-in-out">
                    {{-- Inisialisasi counter nomor urut pasien --}}
                    @php $no = 1; @endphp

                    @forelse($todayOrders as $order)
                        @foreach($order->orderDetails as $detail)
                            <tr class="hover:bg-brand-light/5 transition-colors">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $no++ }}</td>
                                
                                <td class="py-3 px-4 font-semibold text-brand-dark">
                                    {{ $detail->patient->nama ?? 'Tanpa Nama' }}
                                </td>
                                
                                <td class="py-3 px-4 font-mono text-brand-slate">
                                    @if(!empty($detail->patient->no_rm) && strlen($detail->patient->no_rm) === 6)
                                        {{-- Mengubah 123456 menjadi 12.34.56 --}}
                                        {{ preg_replace("/([0-9]{2})([0-9]{2})([0-9]{2})/", "$1.$2.$3", $detail->patient->no_rm) }}
                                    @else
                                        {{ $detail->patient->no_rm ?? '-' }}
                                    @endif
                                </td>
                                
                                <td class="py-3 px-4 text-brand-slate">
                                    {{ $detail->patient->kamar ?? '-' }}
                                </td>
                                
                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @php
                                            $makanan = [];
                                            if($detail->nasi) $makanan[] = 'Nasi';
                                            if($detail->bubur) $makanan[] = 'Bubur';
                                            if($detail->makanan_cair) $makanan[] = 'Msk. Cair / Susu';
                                            if($detail->bs) $makanan[] = 'Bubur Saring';
                                            if($detail->sonde) $makanan[] = 'Sonde';
                                        @endphp
                                        
                                        @forelse($makanan as $item)
                                            <span class="inline-flex items-center bg-brand-light/30 text-brand-primary text-xs font-bold px-2 py-1 rounded-md">
                                                {{ $item }}
                                            </span>
                                        @empty
                                            <span class="text-brand-gray text-xs italic">Belum memilih</span>
                                        @endforelse
                                    </div>
                                </td>
                
                                <td class="py-3 px-4 text-brand-slate">
                                    {{ $detail->diet_pasien ?? '-' }}
                                </td>
                                
                                <td class="py-3 px-4 text-xs text-brand-gray italic">
                                    {{ $detail->keterangan ?? 'Tidak ada keterangan' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('bangsal.orders.edit', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-xs rounded-lg transition-colors border border-amber-200 shadow-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-brand-gray">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-utensils text-2xl text-brand-light"></i>
                                    <p class="text-sm font-medium">Belum ada permintaan makanan yang dikirim hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>