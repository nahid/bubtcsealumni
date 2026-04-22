@extends('layouts.app')

@section('title', $jobPost->title)

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Job Board
        </a>

        <x-card>
            {{-- Header --}}
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $jobPost->title }}</h1>
                    @if($jobPost->company_name)
                        <p class="text-sm font-semibold text-indigo-600 mt-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $jobPost->company_name }}
                        </p>
                    @endif
                    <div class="flex items-center gap-2 mt-2">
                        <x-avatar :name="$jobPost->user->name" size="xs" />
                        <p class="text-sm text-gray-500">
                            <span class="font-medium text-gray-700">{{ $jobPost->user->name }}</span>
                            · {{ $jobPost->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @if($jobPost->isOpen())
                    <x-badge color="success" size="md">Open</x-badge>
                @else
                    <x-badge color="danger" size="md">Closed</x-badge>
                @endif
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @if($jobPost->salary)
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Salary</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $jobPost->salary }}</p>
                    </div>
                @endif
                <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Expires</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $jobPost->expiry_date->format('F d, Y') }}</p>
                </div>
            </div>

            {{-- Description --}}
            @if($jobPost->description)
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-2">Description</p>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {!! $jobPost->description !!}
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            <div class="mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-2">Tags</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($jobPost->tags as $tag)
                        <a href="{{ route('jobs.index', ['tag' => $tag->slug]) }}" class="no-underline">
                            <x-badge color="primary" class="hover:opacity-80 transition">#{{ $tag->name }}</x-badge>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Apply Button --}}
            <div class="pt-4 border-t border-gray-100">
                <x-button variant="primary" size="lg" href="{{ $jobPost->external_link }}" target="_blank" rel="noopener noreferrer">
                    Apply Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </x-button>
            </div>
        </x-card>
    </div>
@endsection

