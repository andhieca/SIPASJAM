<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIGAP') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased relative min-h-screen">
        @include('components.page-loader')
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-pj-green-900/60 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Modal-like Container -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl overflow-hidden sm:rounded-3xl">
                
                <div class="flex flex-col items-center justify-center mb-8">
                    <a href="/">
                        <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-24 h-24 object-contain drop-shadow-xl hover:scale-105 transition-transform duration-300">
                    </a>
                    <h2 class="mt-4 text-2xl font-bold text-white tracking-tight drop-shadow-md">SIGAP</h2>
                    <p class="text-sm text-gray-200 mt-1 font-light tracking-wide text-center">Sistem Informasi Gambaran Pasirjambu</p>
                </div>

                {{ $slot }}

                <div class="mt-8 text-center">
                    <a href="/" class="text-sm text-white/70 hover:text-white hover:underline transition-colors">
                        &larr; Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
