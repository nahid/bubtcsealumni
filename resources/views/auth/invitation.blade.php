@extends('layouts.guest')

@section('title', 'Accept Invitation')

@section('content')
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Welcome, {{ $user->name }}!</h2>
        <p class="text-sm text-gray-500 mt-1">Set your password to activate your alumni account</p>
    </div>

    <form method="POST" action="{{ route('invitation.store', ['token' => $token]) }}" class="space-y-5">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">

        <x-input name="email_display" label="Email" type="email" value="{{ $email }}" disabled readonly />

        <x-input name="password" label="New Password" type="password" :error="$errors->first('password')" required autofocus placeholder="••••••••" />

        <x-input name="password_confirmation" label="Confirm Password" type="password" required placeholder="••••••••" />

        <x-button variant="primary" class="w-full">Set Password & Activate Account</x-button>
    </form>
@endsection
