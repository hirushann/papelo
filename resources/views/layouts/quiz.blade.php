<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Papelo Exam' }}</title>
    <meta name="description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">
    
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Papelo Exam' }}">
    <meta property="og:description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? 'Papelo Exam' }}">
    <meta property="twitter:description" content="{{ $description ?? 'Papelo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.' }}">

    <link rel="icon" href="{{ asset('images/papelo-icon-tile.svg') }}" type="image/svg+xml">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT,WONK@9..144,300..700,0..100,0..1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- @fluxStyles -->
    @livewireStyles
    <style>
      body { font-family: 'Inter', sans-serif; }
      .font-display { font-family: 'Fraunces', serif; font-variation-settings: 'opsz' 48, 'wght' 480, 'SOFT' 10, 'WONK' 0; }
    </style>
</head>
<body class="bg-paper text-ink antialiased">

    {{ $slot }}

    @fluxScripts
    @livewireScripts
</body>
</html>
