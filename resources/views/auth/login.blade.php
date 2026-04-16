@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Welcome back</h2>
        <p class="text-sm text-gray-500 mt-1">Sign in to your alumni account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-input name="email" label="Email Address" type="email" :error="$errors->first('email')" value="{{ old('email') }}" required autofocus placeholder="you@example.com" />

        <x-input name="password" label="Password" type="password" :error="$errors->first('password')" required placeholder="••••••••" />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <x-button variant="primary" class="w-full">Sign In</x-button>

        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Register</a>
        </p>
    </form>
@endsection

