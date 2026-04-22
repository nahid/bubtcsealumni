@extends('layouts.app')

@section('title', 'Create Alumni User')

@section('content')
    <x-page-header title="Create Alumni User" subtitle="Invite a new alumnus. They'll receive an email to set their password." />

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <x-input name="name" label="Full Name" :error="$errors->first('name')" value="{{ old('name') }}" required autofocus placeholder="Full name" />

            <x-input name="email" label="Email Address" type="email" :error="$errors->first('email')" value="{{ old('email') }}" required placeholder="you@example.com" />

            <x-input name="mobile" label="Mobile Number" :error="$errors->first('mobile')" value="{{ old('mobile') }}" required placeholder="01XXXXXXXXX" />

            <div class="grid grid-cols-2 gap-4">
                <x-input name="intake" label="Intake (Batch)" type="number" :error="$errors->first('intake')" value="{{ old('intake') }}" required min="1" placeholder="e.g. 6" />

                <x-select name="shift" label="Shift" :error="$errors->first('shift')" :options="['day' => 'Day', 'evening' => 'Evening']" :selected="old('shift', 'day')" :placeholder="false" required />
            </div>

            <x-select
                name="role"
                label="Role"
                :error="$errors->first('role')"
                :options="collect($roles)->mapWithKeys(fn ($r) => [$r->value => $r->label()])->toArray()"
                :selected="old('role', 'member')"
                :placeholder="false"
                required
            />

            <div class="flex items-center gap-3 pt-2">
                <x-button variant="primary" type="submit">Send Invitation</x-button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
