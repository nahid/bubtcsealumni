@extends('layouts.app')

@section('title', 'Manage Notices')

@section('content')
    <x-page-header title="Notices & Events" subtitle="Manage notices and events visible to all alumni.">
        <x-button variant="primary" :href="route('notices.create')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Notice
        </x-button>
    </x-page-header>

    {{-- Search & Filters --}}
    <x-card class="mb-6">
        <form method="GET" action="{{ route('notices.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title…"
                           class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div>
                    <select name="type" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Types</option>
                        <option value="notice" @selected(request('type') === 'notice')>Notice</option>
                        <option value="event" @selected(request('type') === 'event')>Event</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                    <x-button variant="primary" type="submit">Filter</x-button>
                </div>
            </div>
            @if (request('search') || request('type') || request('status'))
                <div class="mt-3">
                    <a href="{{ route('notices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">✕ Clear filters</a>
                </div>
            @endif
        </form>
    </x-card>

    @if ($notices->isEmpty())
        <x-card>
            <x-empty-state icon="document" title="No notices yet" description="Get started by creating your first notice or event.">
                <x-button variant="primary" size="sm" :href="route('notices.create')">Create Notice</x-button>
            </x-empty-state>
        </x-card>
    @else
        <div class="space-y-3">
            @foreach ($notices as $notice)
                <x-card :hover="true">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <x-badge :color="$notice->type === 'event' ? 'info' : 'neutral'">
                                    @if ($notice->type === 'event')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    @endif
                                    {{ ucfirst($notice->type) }}
                                </x-badge>
                                @if ($notice->is_published)
                                    <x-badge color="success">Published</x-badge>
                                @else
                                    <x-badge color="warning">Draft</x-badge>
                                @endif
                                @if ($notice->type === 'event' && $notice->registrations_count > 0)
                                    <x-badge color="primary">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $notice->registrations_count }} {{ Str::plural('participant', $notice->registrations_count) }}
                                    </x-badge>
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
                            @if ($notice->type === 'event' && $notice->hasRegistrationForm())
                                <x-button variant="ghost" size="xs" :href="route('notices.participants', $notice)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Participants
                                </x-button>
                            @endif
                            <x-button variant="ghost" size="xs" :href="route('notices.edit', $notice)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </x-button>
                            <form method="POST" action="{{ route('notices.destroy', $notice) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                                @csrf
                                @method('DELETE')
                                <x-button variant="danger" size="xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </x-button>
                            </form>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notices->links() }}
        </div>
    @endif
@endsection

