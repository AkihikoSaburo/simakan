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
            :button-text="'Dashboard Dapur'"
            :button-route="route('dapur.dashboard')"
            :button-icon="'fa-solid fa-chart-line'"
        />

        <x-dapur.order-monitoring :orders="$orders" />

    </main>

    <x-layout.footer />

</body>

</html>