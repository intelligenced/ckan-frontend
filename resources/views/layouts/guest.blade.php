<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans leading-normal tracking-normal">

        <!-- Top Banner -->
        <header class="bg-teal-800 p-4 shadow-md">
            <div class="container mx-auto">
                <h1 class="text-3xl text-white font-bold">Data MV</h1>
            </div>
        </header>

<!-- Main Content -->
<div class="container mx-auto">

    {{ $slot }}

</div>

</body>
</html>


