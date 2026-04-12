@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Alumni Directory</h1>
        <p class="mt-1 text-sm text-gray-500">Find and connect with verified BUBT CSE alumni.</p>
    </div>

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('directory.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, or intake…"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="intake" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Intakes</option>
                    @foreach ($intakes as $intake)
                        <option value="{{ $intake }}" @selected(request('intake') == $intake)>Intake {{ $intake }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="shift" class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Shifts</option>
                    <option value="day" @selected(request('shift') === 'day')>Day</option>
                    <option value="evening" @selected(request('shift') === 'evening')>Evening</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Search
                </button>
            </div>
        </div>
    </form>

    {{-- Alumni Grid --}}
    @if ($alumni->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="mt-3 text-sm text-gray-500">No alumni found matching your criteria.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($alumni as $alumnus)
                <a href="{{ route('profile.show', $alumnus) }}" class="block bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition p-5">
                    <div class="flex items-center gap-4">
                        {{-- Avatar --}}
                        <div class="shrink-0">
                            @if ($alumnus->profile_photo)
                                <img src="{{ Storage::disk('public')->url($alumnus->profile_photo) }}"
                                     alt="{{ $alumnus->name }}"
                                     class="h-14 w-14 rounded-full object-cover border-2 border-indigo-100">
                            @else
                                <div class="h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-lg">
                                    {{ strtoupper(substr($alumnus->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $alumnus->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $alumnus->email }}</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="inline-block px-2 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">
                                    Intake {{ $alumnus->intake }}
                                </span>
                                <span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full capitalize">
                                    {{ $alumnus->shift }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($alumnus->bio)
                        <p class="mt-3 text-xs text-gray-500 line-clamp-2">{{ $alumnus->bio }}</p>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $alumni->links() }}
        </div>
    @endif
@endsection

