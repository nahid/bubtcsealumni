@extends('layouts.app')

@section('title', 'Manage Notices')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notices & Events</h1>
            <p class="mt-1 text-sm text-gray-500">Manage notices and events visible to all alumni.</p>
        </div>
        <a href="{{ route('notices.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Notice
        </a>
    </div>

    @if ($notices->isEmpty())
        <div class="text-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm">
            <svg class="mx-auto h-14 w-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">No notices yet</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first notice or event.</p>
            <a href="{{ route('notices.create') }}"
               class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Notice
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($notices as $notice)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:border-gray-300 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notice->type === 'event' ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/10' : 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10' }}">
                                    @if ($notice->type === 'event')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    @endif
                                    {{ ucfirst($notice->type) }}
                                </span>
                                @if ($notice->is_published)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 leading-snug">{{ $notice->title }}</h3>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5 text-xs text-gray-400">
                                <span>by {{ $notice->user->name }}</span>
                                @if ($notice->event_date)
                                    <span class="inline-flex items-center gap-1 text-indigo-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $notice->event_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span>{{ $notice->created_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('notices.edit', $notice) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('notices.destroy', $notice) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notices->links() }}
        </div>
    @endif
@endsection

