@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dapur - SIMAKAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav
        class="sticky top-0 z-50 bg-brand-snow border-b border-brand-light px-6 py-4 flex items-center justify-between shadow-sm">

        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-primary block">
                SIMAKAN RSUD
            </span>

            <h1 class="text-lg font-extrabold text-brand-dark tracking-tight -mt-1">
                Dashboard Dapur
            </h1>
        </div>

        <div class="flex items-center space-x-4">

            <div class="text-right hidden md:block">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-light text-brand-primary border border-brand-primary/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
                    Dapur {{ Str::title(auth()->user()->username) }}
                </span>
            </div>

            <div
                class="h-10 w-10 rounded-xl bg-brand-snow border border-brand-light flex items-center justify-center text-brand-gray shadow-inner">
                <i class="fa-solid fa-utensils text-lg"></i>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf

                <button type="submit" class="text-brand-gray hover:text-rose-600 p-2 rounded-lg transition-colors"
                    title="Keluar">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </button>
            </form>

        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        {{-- HERO --}}
        <div
            class="bg-gradient-to-r from-brand-primary to-brand-accent rounded-2xl p-6 md:p-8 shadow-xl shadow-brand-light text-brand-snow">

            <h2 class="text-2xl md:text-3xl font-black tracking-tight">
                Monitoring Permintaan Makanan 🍽️
            </h2>

            <p class="mt-2 text-sm md:text-base text-brand-light/90">
                Pantau seluruh permintaan makanan pasien yang dikirim oleh bangsal hari ini.
            </p>

            <div class="mt-4">
                <a href="{{ route('dapur.history') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-brand-dark hover:bg-brand-dark/90 transition-all font-bold text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Riwayat Permintaan
                </a>
            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <div class="bg-white border border-brand-light rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase font-bold text-brand-gray">
                    Total Nasi
                </p>

                <h3 class="mt-2 text-3xl font-black text-brand-primary">
                    {{ $orders->count() }}
                </h3>
            </div>

            <div class="bg-white border border-brand-light rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase font-bold text-brand-gray">
                    Total Bubur
                </p>

                <h3 class="mt-2 text-3xl font-black text-brand-primary">
                    {{ $orders->pluck('bangsal_id')->unique()->count() }}
                </h3>
            </div>

            <div class="bg-white border border-brand-light rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase font-bold text-brand-gray">
                    Total Makanan Cair
                </p>

                <h3 class="mt-2 text-3xl font-black text-brand-primary">
                    {{ $orders->sum(fn($order) => $order->orderDetails->count()) }}
                </h3>
            </div>

            <div class="bg-white border border-brand-light rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase font-bold text-brand-gray">
                    Total Sonde
                </p>

                <h3 class="mt-2 text-3xl font-black text-brand-primary">
                    {{ $orders->sum(fn($order) => $order->orderDetails->count()) }}
                </h3>
            </div>

            <div class="bg-white border border-brand-light rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase font-bold text-brand-gray">
                    Tanggal
                </p>

                <h3 class="mt-2 text-lg font-bold text-brand-dark">
                    {{ now()->format('d M Y') }}
                </h3>
            </div>

        </div>

        {{-- EMPTY STATE --}}
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

            {{-- CARD ORDER --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @foreach($orders as $order)

                    @php
                        $details = $order->orderDetails;

                        $nasi = $details->where('nasi', true)->count();
                        $bubur = $details->where('bubur', true)->count();
                        $cair = $details->where('makanan_cair', true)->count();
                        $sonde = $details->where('sonde', true)->count();
                    @endphp

                    <div class="bg-white border border-brand-light rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all">

                        <div class="flex justify-between items-start">

                            <div>
                                <h3 class="font-black text-lg text-brand-dark">
                                    {{ $order->bangsal->nama_bangsal }}
                                </h3>

                                <p class="text-xs text-brand-gray">
                                    Dikirim {{ $order->created_at->format('H:i') }}
                                </p>
                            </div>

                            <div class="text-2xl">
                                🏥
                            </div>

                        </div>

                        <div class="mt-5 space-y-2 text-sm">

                            <div class="flex justify-between">
                                <span>Total Pasien</span>
                                <strong>{{ $details->count() }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>🍚 Nasi</span>
                                <strong>{{ $nasi }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>🥣 Bubur</span>
                                <strong>{{ $bubur }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>🧃 Makanan Cair</span>
                                <strong>{{ $cair }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>🩺 Sonde</span>
                                <strong>{{ $sonde }}</strong>
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

                @endforeach

            </div>

        @endif

    </main>

    <footer class="text-center py-6 text-xs text-brand-gray border-t border-brand-light">
        &copy; 2026 RSUD Andi Makkasau. Sistem Berjalan pada Server Lokal Jaringan Internal.
    </footer>

</body>

</html>