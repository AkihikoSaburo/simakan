@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bangsal - SIMAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-snow font-sans text-brand-dark antialiased min-h-screen flex flex-col">

    <x-layout.navbar 
    title="Digitalisasi Form Makanan" 
    role="Bangsal" 
    :username="auth()->user()->username" 
    icon="fa-user-nurse" 
    />  
    
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <x-ui.welcome-card
            title="Selamat Datang di Portal Bangsal 👋"
            description="Silakan input lembar permintaan makanan pasien Anda hari ini."
            :button-text="'Buat Permintaan Baru'"
            :button-route="route('bangsal.orders.create')"
            :button-icon="'fa-solid fa-circle-plus'"
        />

        <div class="bg-brand-snow rounded-xl border border-brand-light shadow-sm overflow-hidden">
            <div
                class="p-5 border-b border-brand-light flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-brand-dark text-lg">Riwayat Pengiriman Form</h3>
                    <p class="text-brand-gray text-xs mt-0.5">Menampilkan seluruh data log permintaan makanan dari Ruang
                        Palem</p>
                </div>

                <div class="flex items-center gap-3">
                    <input type="date"
                        class="bg-brand-snow border border-brand-light text-brand-dark rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
                    <button
                        class="bg-brand-primary text-brand-snow font-semibold text-sm px-4 py-1.5 rounded-lg hover:bg-brand-primary/95 transition-colors">
                        <i class="fa-solid fa-filter mr-1.5"></i>Filter
                    </button>
                </div>
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
                                    <a href="{{ route('bangsal.orders.show', $order) }}"
                                        class="text-brand-primary hover:text-brand-accent font-bold text-xs inline-flex items-center gap-1 bg-brand-light/60 hover:bg-brand-light px-3 py-1.5 rounded-lg transition-colors">

                                        <i class="fa-solid fa-eye"></i>
                                        Lihat Detail

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
                <span>Menampilkan 2 form terbaru</span>
                <div class="inline-flex shadow-sm rounded-lg overflow-hidden border border-brand-light">
                    <button
                        class="bg-brand-snow px-3 py-1.5 hover:bg-brand-light font-medium text-brand-dark">Previous</button>
                    <button
                        class="bg-brand-snow px-3 py-1.5 hover:bg-brand-light font-medium text-brand-dark">Next</button>
                </div>
            </div>
        </div>
    </main>

    <x-layout.footer />

</body>

</html>
