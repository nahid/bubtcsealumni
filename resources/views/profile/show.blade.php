@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Back Link --}}
        <a href="{{ route('directory.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Directory
        </a>

        {{-- Profile Header Card --}}
        <x-card :padding="false" class="overflow-hidden mb-6">
            {{-- Cover --}}
            <div class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 h-40 sm:h-48 relative">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2240%22%20height%3D%2240%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M0%2040L40%200H20L0%2020zM40%2040V20L20%2040z%22%20fill%3D%22rgba(255%2C255%2C255%2C0.05)%22/%3E%3C/svg%3E')]"></div>

                {{-- Edit button on cover --}}
                @if (auth()->id() === $user->id)
                    <div class="absolute top-4 right-4">
                        <x-button variant="secondary" size="sm" :href="route('profile.edit')" class="!bg-white/90 backdrop-blur-sm hover:!bg-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profile
                        </x-button>
                    </div>
                @endif
            </div>

            <div class="px-6 sm:px-8 pb-6">
                {{-- Avatar + Name Row --}}
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-16 sm:-mt-16 relative z-10">
                    {{-- Avatar --}}
                    @if ($user->profile_photo)
                        <img src="{{ Storage::disk('public')->url($user->profile_photo) }}"
                             alt="{{ $user->name }}"
                             class="h-28 w-28 sm:h-32 sm:w-32 rounded-2xl object-cover border-4 border-white shadow-lg shrink-0">
                    @else
                        <div class="h-28 w-28 sm:h-32 sm:w-32 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 border-4 border-white shadow-lg flex items-center justify-center text-white font-bold text-4xl shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    {{-- Name & Title --}}
                    <div class="pb-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            @if ($user->board_position)
                                <x-badge color="primary">{{ $user->board_position->label() }}</x-badge>
                            @endif
                            @if ($user->role !== \App\Enums\UserRole::Member)
                                <x-badge :color="$user->role->value === 'admin' ? 'danger' : 'warning'" size="xs">{{ $user->role->label() }}</x-badge>
                            @endif
                        </div>
                        @if ($user->alumni_id)
                            <p class="text-sm text-gray-400 font-mono mt-1">{{ $user->alumni_id }}</p>
                        @endif
                    </div>
                </div>

                {{-- Quick Info Pills — all clickable --}}
                <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500">
                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $user->email }}
                    </a>
                    @if ($user->mobile)
                        <a href="tel:{{ $user->mobile }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $user->mobile }}
                        </a>
                    @endif
                    @if ($user->whatsapp_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp_number) }}" target="_blank" class="inline-flex items-center gap-1.5 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Intake {{ $user->intake }} &middot; {{ ucfirst($user->shift) }} Shift
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Joined {{ $user->created_at->format('M Y') }}
                    </span>
                </div>

                {{-- Social Links Row --}}
                @if ($user->facebook_url || $user->linkedin_url || $user->website_url)
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        @if ($user->facebook_url)
                            <a href="{{ $user->facebook_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                        @endif
                        @if ($user->linkedin_url)
                            <a href="{{ $user->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 text-sm font-medium hover:bg-sky-100 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                LinkedIn
                            </a>
                        @endif
                        @if ($user->website_url)
                            <a href="{{ $user->website_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-medium hover:bg-indigo-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                Website
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </x-card>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Bio + Activity --}}
            <div class="lg:col-span-2 space-y-6">
                @if ($user->bio)
                    <x-card>
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">About</h2>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $user->bio }}</p>
                    </x-card>
                @endif

                {{-- Activity placeholder for own profile --}}
                @if (auth()->id() === $user->id && !$user->bio)
                    <x-card>
                        <x-empty-state icon="document" title="No bio yet" description="Add a bio to let other alumni know more about you.">
                            <x-button variant="primary" size="sm" :href="route('profile.edit')">Add Bio</x-button>
                        </x-empty-state>
                    </x-card>
                @endif

                {{-- Job Posts --}}
                @if ($user->job_posts_count > 0)
                    <x-card>
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Job Posts</h2>
                        <p class="text-sm text-gray-500">
                            {{ $user->name }} has shared {{ $user->job_posts_count }} {{ Str::plural('job opportunity', $user->job_posts_count) }} with the alumni community.
                        </p>
                    </x-card>
                @endif
            </div>

            {{-- Right Column: Details + Quick Actions --}}
            <div class="space-y-6">
                {{-- Details Card --}}
                <x-card>
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Details</h2>
                    <dl class="space-y-3">
                        <div class="flex items-start gap-3">
                            <dt>
                                <svg class="w-4.5 h-4.5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </dt>
                            <dd>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Intake & Shift</p>
                                <p class="text-sm font-medium text-gray-700">Intake {{ $user->intake }} &middot; {{ ucfirst($user->shift) }}</p>
                            </dd>
                        </div>

                        @if ($user->alumni_id)
                            <div class="flex items-start gap-3">
                                <dt>
                                    <svg class="w-4.5 h-4.5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                </dt>
                                <dd>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Alumni ID</p>
                                    <p class="text-sm font-mono font-medium text-gray-700">{{ $user->alumni_id }}</p>
                                </dd>
                            </div>
                        @endif

                        @if ($user->board_position)
                            <div class="flex items-start gap-3">
                                <dt>
                                    <svg class="w-4.5 h-4.5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </dt>
                                <dd>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Board Position</p>
                                    <p class="text-sm font-medium text-indigo-600">{{ $user->board_position->label() }}</p>
                                </dd>
                            </div>
                        @endif

                        @if ($user->company_name || $user->designation)
                            <div class="flex items-start gap-3">
                                <dt>
                                    <svg class="w-4.5 h-4.5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </dt>
                                <dd>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Work</p>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $user->designation ?? '' }}{{ $user->designation && $user->company_name ? ' at ' : '' }}@if ($user->company_website)<a href="{{ $user->company_website }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 hover:underline">{{ $user->company_name }}</a>@else{{ $user->company_name ?? '' }}@endif
                                    </p>
                                </dd>
                            </div>
                        @endif

                        @if ($user->current_city)
                            <div class="flex items-start gap-3">
                                <dt>
                                    <svg class="w-4.5 h-4.5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </dt>
                                <dd>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Location</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $user->current_city }}</p>
                                </dd>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            </dt>
                            <dd>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Member Since</p>
                                <p class="text-sm font-medium text-gray-700">{{ $user->created_at->format('F j, Y') }}</p>
                            </dd>
                        </div>

                        @if ($user->status === 'verified')
                            <div class="flex items-start gap-3">
                                <dt>
                                    <svg class="w-4.5 h-4.5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </dt>
                                <dd>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Status</p>
                                    <p class="text-sm font-medium text-emerald-600">Verified Alumni</p>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </x-card>

                {{-- Quick Contact --}}
                <x-card>
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Quick Contact</h2>
                    <div class="space-y-2.5">
                        <a href="mailto:{{ $user->email }}" class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 text-sm font-medium hover:bg-indigo-100 transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Send Email
                        </a>

                        @if ($user->whatsapp_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp_number) }}" target="_blank"
                               class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium hover:bg-emerald-100 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                        @endif

                        @if ($user->mobile)
                            <a href="tel:{{ $user->mobile }}" class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Call {{ $user->mobile }}
                            </a>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection

