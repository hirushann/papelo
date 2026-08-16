<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Papelo') }}</title>
        <meta name="description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">
        
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? config('app.name', 'Papelo') }}">
        <meta property="og:description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">
        
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ $title ?? config('app.name', 'Papelo') }}">
        <meta property="twitter:description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">
        <link rel="icon" href="{{ asset('images/papelo-icon-tile.svg') }}" type="image/svg+xml">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        @fluxScripts
    </body>
</html>
