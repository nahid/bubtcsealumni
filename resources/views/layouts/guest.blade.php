<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BUBTAlumni') }} — @yield('title', 'Welcome')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md animate-fade-in">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <span class="text-white text-lg font-bold">B</span>
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight group-hover:text-indigo-600 transition-colors">BUBTAlumni</span>
            </a>
            <p class="text-sm text-gray-500 mt-2">BUBT CSE Alumni Network</p>
        </div>

        {{-- Flash Messages --}}
        <div class="space-y-3 mb-4">
            @if (session('success'))
                <x-alert type="success" :dismissible="true">{{ session('success') }}</x-alert>
            @endif
            @if (session('error'))
                <x-alert type="error" :dismissible="true">{{ session('error') }}</x-alert>
            @endif
        </div>

        {{-- Card --}}
        <x-card class="p-8">
            @yield('content')
        </x-card>

        {{-- Footer link --}}
        <p class="text-center text-xs text-gray-400 mt-8">&copy; {{ date('Y') }} BUBT CSE Alumni Network</p>
    </div>
</body>
</html>

