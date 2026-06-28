<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Arsip Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col" x-data>

    <x-layout.navbar title="Gudang Arsip" icon="fa-archive" />


    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center w-9 h-9 bg-white border border-brand-light text-brand-gray hover:text-brand-dark hover:border-brand-primary/40 rounded-xl shadow-sm transition-all hover:scale-105"
                title="Kembali ke Dashboard Admin">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-sm font-bold text-brand-dark">Menu Utama Admin</h2>
                <p class="text-[11px] text-brand-gray">Kembali ke panel kendali dashboard.</p>
            </div>
        </div>

        <x-ui.welcome-card title="Gudang Arsip Bangsal 📦"
            description="Pusat dokumentasi dan rekapitulasi data historis dari bangsal yang sudah dinonaktifkan. Anda tetap dapat meninjau seluruh riwayat pasien dan pemesanan makanan terdahulu untuk keperluan audit internal." />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bangsalsDiarsip as $bangsal)
                <div
                    class="group bg-white rounded-2xl border border-brand-light p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-primary/30 hover:shadow-md flex flex-col justify-between">

                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3
                                    class="text-lg font-bold text-brand-dark group-hover:text-brand-primary transition-colors">
                                    {{ $bangsal->nama_bangsal }}
                                </h3>
                                <p class="text-[11px] text-brand-gray mt-0.5">
                                    <i class="fa-regular fa-clock mr-0.5"></i> Diarsipkan:
                                    {{ $bangsal->deleted_at->translatedFormat('d M Y • H:i') }}
                                </p>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-brand-gray">
                                <i class="fa-solid fa-box-archive text-base"></i>
                            </div>
                        </div>

                        <div class="space-y-2 border-t border-brand-light/60 pt-3 mb-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-brand-gray">Total Histori Pasien</span>
                                <span class="font-semibold text-brand-dark">{{ $bangsal->patients_count }} Pasien</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-brand-gray">Total Permintaan Makanan</span>
                                <span class="font-semibold text-brand-dark">{{ $bangsal->orders_count }} Pesanan</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.bangsals.arsip.show', $bangsal->id) }}"
                        class="w-full flex justify-center items-center gap-2 py-2.5 px-4 bg-brand-primary text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-brand-primary/90 transition-all active:scale-[0.98]">
                        <i class="fa-solid fa-eye text-xs"></i> Lihat Riwayat Pesanan
                    </a>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-brand-light p-12 text-center">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-folder-open text-4xl text-brand-light"></i>
                        <h4 class="font-bold text-brand-dark mt-2">Gudang Arsip Kosong</h4>
                        <p class="text-xs text-brand-gray">Belum ada data bangsal yang diarsipkan saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <x-layout.footer />
</body>

</html>