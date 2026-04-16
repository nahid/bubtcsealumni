@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
    <x-page-header title="Alumni Directory" subtitle="Find and connect with verified BUBT CSE alumni." />

    {{-- Search & Filters --}}
    <x-card class="mb-8">
        <form method="GET" action="{{ route('directory.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or intake…"
                            class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                </div>
                <div>
                    <select name="intake" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Intakes</option>
                        @foreach ($intakes as $intake)
                            <option value="{{ $intake }}" @selected(request('intake') == $intake)>Intake {{ $intake }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <select name="shift" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Shifts</option>
                        <option value="day" @selected(request('shift') === 'day')>Day</option>
                        <option value="evening" @selected(request('shift') === 'evening')>Evening</option>
                    </select>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Search
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    {{-- Alumni Grid --}}
    @if ($alumni->isEmpty())
        <x-card>
            <x-empty-state icon="users" title="No alumni found" description="No alumni found matching your criteria. Try adjusting your filters." />
        </x-card>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($alumni as $alumnus)
                <a href="{{ route('profile.show', $alumnus) }}" class="group">
                    <x-card :hover="true" class="h-full">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                @if ($alumnus->profile_photo)
                                    <img src="{{ Storage::disk('public')->url($alumnus->profile_photo) }}"
                                         alt="{{ $alumnus->name }}"
                                         class="h-14 w-14 rounded-full object-cover ring-2 ring-indigo-100">
                                @else
                                    <x-avatar :name="$alumnus->name" size="lg" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $alumnus->name }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $alumnus->email }}</p>
                                <div class="mt-1.5 flex items-center gap-1.5">
                                    <x-badge color="primary" size="xs">Intake {{ $alumnus->intake }}</x-badge>
                                    <x-badge color="neutral" size="xs">{{ ucfirst($alumnus->shift) }}</x-badge>
                                </div>
                            </div>
                        </div>
                        @if ($alumnus->bio)
                            <p class="mt-3 text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $alumnus->bio }}</p>
                        @endif
                    </x-card>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $alumni->links() }}
        </div>
    @endif
@endsection

