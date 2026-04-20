<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'BUBTAlumni') }} — @yield('title', 'Welcome')</title>
    <meta name="description" content="BUBT CSE Alumni Network — Stay connected, find opportunities, and grow together with fellow alumni.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100" id="navbar">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-lg flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm font-bold">B</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 tracking-tight group-hover:text-indigo-600 transition-colors">BUBTAlumni</span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="#features" class="text-sm font-medium text-gray-500 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Features</a>
                    <a href="#events" class="text-sm font-medium text-gray-500 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Events</a>
                    <a href="#alumni-voice" class="text-sm font-medium text-gray-500 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Alumni Voice</a>
                    <a href="#membership" class="text-sm font-medium text-gray-500 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Membership</a>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors px-3 py-2">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-lg hover:from-indigo-700 hover:to-violet-700 transition-all shadow-sm">
                        Join Now
                    </a>
                </div>

                {{-- Mobile Hamburger --}}
                <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-100 mt-1 pt-3 space-y-1">
                <a href="#features" class="block text-sm font-medium text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Features</a>
                <a href="#events" class="block text-sm font-medium text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Events</a>
                <a href="#alumni-voice" class="block text-sm font-medium text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Alumni Voice</a>
                <a href="#membership" class="block text-sm font-medium text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">Membership</a>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-bold">B</span>
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">BUBTAlumni</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm">The official alumni network for BUBT's Department of CSE. Connecting graduates, fostering growth, building futures.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#events" class="hover:text-white transition-colors">Events</a></li>
                        <li><a href="#alumni-voice" class="hover:text-white transition-colors">Alumni Voice</a></li>
                        <li><a href="#membership" class="hover:text-white transition-colors">Membership</a></li>
                    </ul>
                </div>

                {{-- Account --}}
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Account</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs">&copy; {{ date('Y') }} BUBT CSE Alumni Network. All rights reserved.</p>
                <p class="text-xs">Built with <span class="text-red-400">♥</span> for BUBT CSE community</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
