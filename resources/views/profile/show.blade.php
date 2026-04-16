@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- Back Link --}}
        <a href="{{ route('directory.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Directory
        </a>

        <x-card :padding="false" class="overflow-hidden">
            {{-- Cover --}}
            <div class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 h-36 relative">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2240%22%20height%3D%2240%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M0%2040L40%200H20L0%2020zM40%2040V20L20%2040z%22%20fill%3D%22rgba(255%2C255%2C255%2C0.05)%22/%3E%3C/svg%3E')]"></div>
            </div>

            <div class="px-6 pb-6">
                {{-- Avatar --}}
                <div class="-mt-14 mb-4">
                    @if ($user->profile_photo)
                        <img src="{{ Storage::disk('public')->url($user->profile_photo) }}"
                             alt="{{ $user->name }}"
                             class="h-28 w-28 rounded-2xl object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="h-28 w-28 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 border-4 border-white shadow-lg flex items-center justify-center text-white font-bold text-3xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Name & Details --}}
                <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
                @if ($user->alumni_id)
                    <p class="text-xs text-gray-400 font-mono mt-1">{{ $user->alumni_id }}</p>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <x-badge color="primary">Intake {{ $user->intake }}</x-badge>
                    <x-badge color="neutral">{{ ucfirst($user->shift) }} Shift</x-badge>
                    <x-badge color="success">{{ $user->job_posts_count }} {{ Str::plural('job post', $user->job_posts_count) }}</x-badge>
                </div>

                {{-- Bio --}}
                @if ($user->bio)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700 mb-2">About</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                    </div>
                @endif

                {{-- Contact & Social Links --}}
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Connect</h2>
                    <div class="flex flex-wrap gap-2">
                        @if ($user->whatsapp_number)
                            <x-button variant="success" size="sm" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp_number) }}" target="_blank">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </x-button>
                        @endif

                        <x-button variant="primary" size="sm" href="mailto:{{ $user->email }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email
                        </x-button>

                        @if ($user->mobile)
                            <x-button variant="secondary" size="sm" href="tel:{{ $user->mobile }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Call
                            </x-button>
                        @endif

                        @if ($user->facebook_url)
                            <x-button variant="primary" size="sm" href="{{ $user->facebook_url }}" target="_blank" rel="noopener noreferrer" class="!bg-blue-600 hover:!bg-blue-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </x-button>
                        @endif

                        @if ($user->linkedin_url)
                            <x-button variant="primary" size="sm" href="{{ $user->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="!bg-sky-700 hover:!bg-sky-800">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                LinkedIn
                            </x-button>
                        @endif

                        @if ($user->website_url)
                            <x-button variant="secondary" size="sm" href="{{ $user->website_url }}" target="_blank" rel="noopener noreferrer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                Website
                            </x-button>
                        @endif
                    </div>
                </div>

                {{-- Edit own profile --}}
                @if (auth()->id() === $user->id)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <x-button variant="ghost" size="sm" :href="route('profile.edit')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit your profile
                        </x-button>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
@endsection

