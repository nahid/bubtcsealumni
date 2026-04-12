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
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 tracking-tight">BUBTAlumni</a>
            <p class="text-sm text-gray-500 mt-1">BUBT CSE Alumni Network</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700 mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>

