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
        <header class="bg-teal-800 p-4 shadow-md ">
            <div class="container mx-auto flex items-center">
                <a href="{{ route('frontpage.index') }}" class="flex items-center ml-4">
                    @include('frontpage.partials.logo')  
                    <h1 class="text-3xl text-white font-bold ml-3">Data MV</h1>
                </a>
            </div>
        </header>



        <!-- Main Content -->
        <div class="container mx-auto">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white p-4">
            <div class="container mx-auto text-center">
                <p>© {{ now()->year }} | This project is a prototype developed for the Master of Science, Design and Research in Open Government Data.</p>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
