@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Create your account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mobile --}}
        <div>
            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
            <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                placeholder="01XXXXXXXXX">
            @error('mobile')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Intake & Shift --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="intake" class="block text-sm font-medium text-gray-700 mb-1">Intake (Batch)</label>
                <input id="intake" type="number" name="intake" value="{{ old('intake') }}" required min="1"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                @error('intake')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="shift" class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                <select id="shift" name="shift" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <option value="day" {{ old('shift') === 'day' ? 'selected' : '' }}>Day</option>
                    <option value="evening" {{ old('shift') === 'evening' ? 'selected' : '' }}>Evening</option>
                </select>
                @error('shift')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Reference Email --}}
        <div>
            <label for="reference_email" class="block text-sm font-medium text-gray-700 mb-1">Reference Email</label>
            <input id="reference_email" type="email" name="reference_email" value="{{ old('reference_email') }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                placeholder="Email of an existing verified alumni">
            @error('reference_email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
            Register
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Log in</a>
        </p>
    </form>
@endsection

