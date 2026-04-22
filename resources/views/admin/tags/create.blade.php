@extends('layouts.app')

@section('title', 'New Tag')

@section('content')
    <div class="max-w-xl mx-auto">
        <x-page-header title="New Tag" subtitle="Create a new tag for job posts and subscriptions.">
            <x-button variant="ghost" size="sm" :href="route('admin.tags.index')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </x-button>
        </x-page-header>

        <x-card>
            <form method="POST" action="{{ route('admin.tags.store') }}" class="space-y-5">
                @csrf

                <x-input name="name" label="Name" :error="$errors->first('name')" value="{{ old('name') }}" required placeholder="e.g. Laravel" />

                <x-input name="slug" label="Slug" :error="$errors->first('slug')" value="{{ old('slug') }}" placeholder="auto-generated from name if empty" hint="Lowercase letters, numbers, and dashes only." />

                <div class="flex items-center gap-3 pt-2">
                    <x-button variant="primary">Create Tag</x-button>
                    <x-button variant="ghost" type="button" :href="route('admin.tags.index')">Cancel</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
