@extends('layouts.app')

@section('title', $jobPost->title)

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-6">
            ← Back to Job Board
        </a>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $jobPost->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Posted by <span class="font-medium text-gray-700">{{ $jobPost->user->name }}</span>
                        · {{ $jobPost->created_at->diffForHumans() }}
                    </p>
                </div>
                @if($jobPost->isOpen())
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-green-50 text-green-700 border border-green-200">Open</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-red-50 text-red-600 border border-red-200">Closed</span>
                @endif
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @if($jobPost->salary)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Salary</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $jobPost->salary }}</p>
                    </div>
                @endif
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Expires</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $jobPost->expiry_date->format('F d, Y') }}</p>
                </div>
            </div>

            {{-- Tags --}}
            <div class="mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-2">Tags</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($jobPost->tags as $tag)
                        <a href="{{ route('jobs.index', ['tag' => $tag->slug]) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Apply Button --}}
            <a href="{{ $jobPost->external_link }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                Apply Now
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
    </div>
@endsection

