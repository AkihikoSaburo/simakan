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

    <x-layout.navbar 
    title="Dashboard Dapur" 
    icon="fa-utensils" 
    />

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card
            title="Monitoring Permintaan Makanan 🍽️"
            description="Pantau seluruh permintaan makanan pasien yang dikirim oleh bangsal hari ini."
            :button-text="'Riwayat Permintaan'"
            :button-route="route('dapur.history')"
            :button-icon="'fa-solid fa-clock-rotate-left'"
        />

        <x-layout.stats-grid
            :stats="[
               [
                   'title' => 'Total Nasi',
                   'value' => $orders->count(),
               ],
               [
                   'title' => 'Total Bubur',
                   'value' => $orders->pluck('bangsal_id')->unique()->count(),
               ],
               [
                   'title' => 'Total Masakan Cair / Susu',
                   'value' => $orders->sum(fn($order) => $order->orderDetails->count()),
               ],
               [
                    'title' => 'Total Bubur Saring',
                    'value' => $orders->sum(fn($order) => $order->orderDetails->count()),
                ],
               [
                   'title' => 'Total Sonde',
                   'value' => $orders->sum(fn($order) => $order->orderDetails->count()),
               ],
           ]"
        />

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

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @foreach($orders as $order)

                    @php
                        $details = $order->orderDetails;

                        $nasi = $details->where('nasi', true)->count();
                        $bubur = $details->where('bubur', true)->count();
                        $cair = $details->where('makanan_cair', true)->count();
                        $bs = $details->where('bs', true)->count();
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
                                <span>Nasi</span>
                                <strong>{{ $nasi }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Bubur</span>
                                <strong>{{ $bubur }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Masakan Cair / Susu</span>
                                <strong>{{ $cair }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Bubur Saring</span>
                                <strong>{{ $bs }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Sonde</span>
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

    <x-layout.footer />

</body>

</html>