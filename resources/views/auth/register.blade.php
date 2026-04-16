@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create your account</h2>
        <p class="text-sm text-gray-500 mt-1">Join the BUBT CSE Alumni Network</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <x-input name="name" label="Full Name" :error="$errors->first('name')" value="{{ old('name') }}" required autofocus placeholder="Your full name" />

        <x-input name="email" label="Email Address" type="email" :error="$errors->first('email')" value="{{ old('email') }}" required placeholder="you@example.com" />

        <x-input name="mobile" label="Mobile Number" :error="$errors->first('mobile')" value="{{ old('mobile') }}" required placeholder="01XXXXXXXXX" />

        <div class="grid grid-cols-2 gap-4">
            <x-input name="intake" label="Intake (Batch)" type="number" :error="$errors->first('intake')" value="{{ old('intake') }}" required min="1" placeholder="e.g. 6" />

            <x-select name="shift" label="Shift" :error="$errors->first('shift')" :options="['day' => 'Day', 'evening' => 'Evening']" :selected="old('shift', 'day')" :placeholder="false" required />
        </div>

        {{-- Reference Emails --}}
        <div class="space-y-4">
            <x-alert type="info">
                Provide emails of two verified alumni who can vouch for you. Both must approve your registration.
            </x-alert>

            <x-input name="reference_email_1" label="Reference 1 Email" type="email" :error="$errors->first('reference_email_1')" value="{{ old('reference_email_1') }}" required placeholder="Email of a verified alumni" />

            <x-input name="reference_email_2" label="Reference 2 Email" type="email" :error="$errors->first('reference_email_2')" value="{{ old('reference_email_2') }}" required placeholder="Email of another verified alumni" />
        </div>

        <x-input name="password" label="Password" type="password" :error="$errors->first('password')" required placeholder="••••••••" />

        <x-input name="password_confirmation" label="Confirm Password" type="password" required placeholder="••••••••" />

        <x-button variant="primary" class="w-full">Create Account</x-button>

        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Sign in</a>
        </p>
    </form>
@endsection

