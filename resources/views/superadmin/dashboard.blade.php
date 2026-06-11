<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-3xl font-bold">
        Dashboard
    </h1>

    <div class="mt-4 p-4 bg-white rounded shadow">

        <p>
            Username:
            <strong>{{ auth()->user()->username }}</strong>
        </p>

        <p>
            Role:
            <strong>{{ auth()->user()->role }}</strong>
        </p>

    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-5">
        @csrf

        <button
            class="px-4 py-2 bg-red-500 text-white rounded">
            Logout
        </button>
    </form>

</div>

</body>
</html>