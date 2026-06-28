<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permintaan Makanan - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">
    <x-layout.navbar title="Digitalisasi Form Makanan" role="Bangsal" :username="auth()->user()->username"
        icon="fa-user-nurse" />
    <main class="flex-1 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Header Info & Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-brand-light shadow-sm">
            <div>
                <span class="text-xs font-bold text-brand-primary uppercase tracking-wider">Detail Permintaan Makanan</span>
                <h2 class="text-2xl font-black text-brand-dark mt-1">Bangsal: {{ $order->bangsal->nama_bangsal }}</h2>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-brand-gray mt-2 font-medium">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-calendar text-brand-primary"></i>
                        {{ $order->tanggal_pesanan->translatedFormat('d F Y') }}
                    </span>
                    <span class="h-3 w-px bg-brand-light hidden sm:inline"></span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-user text-brand-primary"></i>
                        Petugas: {{ $order->creator->username ?? '-' }}
                    </span>
                    <span class="h-3 w-px bg-brand-light hidden sm:inline"></span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-hashtag text-brand-primary"></i>
                        Form ID: #{{ $order->id }}
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dapur.dashboard') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 border border-brand-light text-brand-slate font-bold text-xs rounded-xl hover:bg-brand-light/30 transition-colors shadow-sm bg-white">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <a href="{{ route('dapur.orders.pdf', $order) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-brand-snow font-bold text-xs rounded-xl shadow-md transition-colors">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
        <!-- Tabel Detail Pasien -->
        <div class="bg-brand-snow rounded-2xl border border-brand-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-brand-light bg-brand-light/10">
                <h3 class="font-bold text-brand-dark text-lg">Daftar Permintaan Pasien</h3>
                <p class="text-brand-gray text-xs mt-0.5">Daftar diet makanan pasien untuk order ini</p>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light text-sm">
                        @php $no = 1; @endphp
                        @forelse($order->orderDetails as $detail)
                            <tr class="hover:bg-brand-light/5 transition-colors align-top">
                                <td class="py-3 px-4 text-center font-bold text-brand-gray">{{ $no++ }}</td>
                                
                                <td class="py-3 px-4 font-semibold text-brand-dark">
                                    {{ $detail->patient->nama ?? 'Tanpa Nama' }}
                                </td>
                                
                                <td class="py-3 px-4 font-mono text-brand-slate">
                                    {{ $detail->patient->no_rm ?? '-' }}
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
                
                                <td class="py-3 px-4 text-brand-slate font-medium">
                                    {{ $detail->diet_pasien ?? '-' }}
                                </td>
                                
                                <td class="py-3 px-4 text-xs text-brand-gray italic">
                                    {{ $detail->keterangan ?? 'Tidak ada keterangan' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-brand-gray">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-utensils text-2xl text-brand-light"></i>
                                        <p class="text-sm font-medium">Belum ada data detail pasien.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Rekapitulasi porsi khusus Order ini -->
        <div class="space-y-3">
            <h3 class="font-bold text-brand-dark text-lg px-1">Rekapitulasi Porsi Form</h3>
            <x-layout.stats-grid :stats="[
                [
                    'title' => 'Total Nasi',
                    'value' => $order->nasi_count,
                ],
                [
                    'title' => 'Total Bubur',
                    'value' => $order->bubur_count,
                ],
                [
                    'title' => 'Total Masakan Cair / Susu',
                    'value' => $order->makanan_cair_count,
                ],
                [
                    'title' => 'Total Bubur Saring',
                    'value' => $order->bs_count,
                ],
                [
                    'title' => 'Total Sonde',
                    'value' => $order->sonde_count,
                ],
            ]" />
        </div>
    </main>
    <x-layout.footer />
</body>
</html>