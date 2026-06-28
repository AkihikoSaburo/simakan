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
            description="Lihat riwayat pengiriman form makanan Anda hari ini."
            :button-text="'Dashboard Bangsal'"
            :button-route="route('bangsal.dashboard')"
            :button-icon="'fa-solid fa-chart-line'" />

        <x-bangsal.order-history :orders="$orders" />

    </main>

    <x-layout.footer />

</body>

</html>