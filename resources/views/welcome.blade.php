@extends('layouts.landing')

@section('title', 'Welcome')

@section('content')

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50/40 to-violet-50/30">
        {{-- Decorative mesh --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-gradient-to-br from-indigo-200/40 to-violet-300/30 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-32 w-80 h-80 bg-gradient-to-tr from-blue-200/30 to-cyan-200/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 right-1/3 w-72 h-72 bg-gradient-to-tl from-violet-200/30 to-fuchsia-200/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24 lg:pt-24 lg:pb-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: Text --}}
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-100/80 text-indigo-700 text-xs font-semibold ring-1 ring-inset ring-indigo-600/10 mb-6">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        BUBT CSE Alumni Network
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-gray-900 tracking-tight leading-[1.1]">
                        Stay Connected.<br>
                        <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Grow Together.</span>
                    </h1>

                    <p class="mt-6 text-lg text-gray-500 leading-relaxed max-w-lg">
                        The official alumni network for BUBT's Department of CSE. Reconnect with batchmates, discover career opportunities, and be part of a thriving professional community.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-start gap-3">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-7 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-base font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30">
                            Join the Network
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="#events"
                           class="inline-flex items-center px-7 py-3 bg-white text-gray-700 text-base font-semibold rounded-xl hover:bg-gray-50 transition-all border border-gray-200 shadow-sm">
                            <svg class="mr-2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Explore Events
                        </a>
                    </div>
                </div>

                {{-- Right: Photo collage --}}
                <div class="relative hidden lg:block">
                    <div class="relative w-full h-[420px]">
                        {{-- Main large photo --}}
                        <div class="absolute top-0 right-0 w-72 h-72 rounded-2xl overflow-hidden shadow-2xl shadow-indigo-500/10 ring-1 ring-white/50 animate-float">
                            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=600&h=600&fit=crop&crop=faces" alt="Alumni gathering" class="w-full h-full object-cover" loading="eager">
                        </div>
                        {{-- Smaller top-left photo --}}
                        <div class="absolute top-8 left-0 w-44 h-44 rounded-2xl overflow-hidden shadow-xl shadow-violet-500/10 ring-1 ring-white/50 animate-float-delayed">
                            <img src="https://images.unsplash.com/photo-1627556704302-624286467c65?w=400&h=400&fit=crop&crop=faces" alt="Graduation ceremony" class="w-full h-full object-cover" loading="eager">
                        </div>
                        {{-- Bottom-left photo --}}
                        <div class="absolute bottom-0 left-8 w-52 h-40 rounded-2xl overflow-hidden shadow-xl shadow-blue-500/10 ring-1 ring-white/50 animate-float" style="animation-delay: 3s;">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=400&fit=crop&crop=faces" alt="Professional networking" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        {{-- Decorative dots --}}
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-[radial-gradient(circle,_#c7d2fe_1.5px,_transparent_1.5px)] bg-[length:10px_10px] opacity-60"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="relative -mt-8 z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-xl shadow-gray-900/5 p-6 sm:p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">{{ number_format($stats['members']) }}</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Verified Members</p>
                </div>
                <div>
                    <p class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">{{ $stats['intakes'] }}</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Intakes</p>
                </div>
                <div>
                    <p class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">{{ number_format($stats['jobs']) }}</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Job Opportunities</p>
                </div>
                <div>
                    <p class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">{{ $stats['cities'] }}</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Cities</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Grid --}}
    <section id="features" class="py-24 bg-white scroll-mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-violet-100/80 text-violet-700 text-xs font-semibold ring-1 ring-inset ring-violet-600/10 mb-4">Platform Features</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Everything You Need to Stay Connected</h2>
                <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">A complete platform built for BUBT CSE alumni to network, grow professionally, and give back.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Feature 1: Job Board --}}
                <div class="relative bg-gradient-to-br from-white to-indigo-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-indigo-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Job Board</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Browse and post job opportunities shared exclusively within the alumni community.</p>
                </div>

                {{-- Feature 2: Alumni Directory --}}
                <div class="relative bg-gradient-to-br from-white to-violet-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-violet-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-violet-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Alumni Directory</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Find and connect with batchmates, seniors, and juniors across intakes and cities.</p>
                </div>

                {{-- Feature 3: Events & Notices --}}
                <div class="relative bg-gradient-to-br from-white to-blue-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-blue-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Events & Notices</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Stay updated with reunions, meetups, workshops, and important announcements.</p>
                </div>

                {{-- Feature 4: Verified Network --}}
                <div class="relative bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-emerald-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Network</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Every member is peer-verified to ensure an authentic, trustworthy community.</p>
                </div>

                {{-- Feature 5: Peer Referral --}}
                <div class="relative bg-gradient-to-br from-white to-amber-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-amber-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Peer Referral</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Join through referrals from existing alumni — building trust from day one.</p>
                </div>

                {{-- Feature 6: Professional Profiles --}}
                <div class="relative bg-gradient-to-br from-white to-rose-50/30 rounded-2xl border border-gray-200/60 p-7 shadow-sm hover:shadow-lg hover:border-rose-200/60 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-rose-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Professional Profiles</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Showcase your career journey, skills, and social links to the community.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100/80 text-indigo-700 text-xs font-semibold ring-1 ring-inset ring-indigo-600/10 mb-4">Getting Started</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">How It Works</h2>
                <p class="mt-4 text-gray-500 text-lg">Get started in three simple steps.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                {{-- Connecting line (desktop only) --}}
                <div class="hidden md:block absolute top-12 left-[calc(16.67%+2rem)] right-[calc(16.67%+2rem)] h-0.5 bg-gradient-to-r from-indigo-300 via-violet-300 to-indigo-300"></div>

                {{-- Step 1 --}}
                <div class="text-center relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-violet-600 text-white rounded-2xl flex items-center justify-center mx-auto text-xl font-bold shadow-xl shadow-indigo-500/25 relative z-10 rotate-3 hover:rotate-0 transition-transform">1</div>
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">Register</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Sign up with your details and provide two alumni references for verification.</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-600 to-purple-600 text-white rounded-2xl flex items-center justify-center mx-auto text-xl font-bold shadow-xl shadow-violet-500/25 relative z-10 -rotate-2 hover:rotate-0 transition-transform">2</div>
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">Get Verified</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Your references confirm your identity, keeping the network authentic and trusted.</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto text-xl font-bold shadow-xl shadow-purple-500/25 relative z-10 rotate-2 hover:rotate-0 transition-transform">3</div>
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">Connect & Grow</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Access the directory, job board, events, and build meaningful professional connections.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Photo Gallery Strip --}}
    <section class="py-16 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl overflow-hidden aspect-[4/3] shadow-lg shadow-gray-900/5">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&h=400&fit=crop" alt="Alumni event" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden aspect-[4/3] shadow-lg shadow-gray-900/5 md:mt-6">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=500&h=400&fit=crop&crop=faces" alt="Community gathering" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden aspect-[4/3] shadow-lg shadow-gray-900/5">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=500&h=400&fit=crop" alt="Professional networking" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden aspect-[4/3] shadow-lg shadow-gray-900/5 md:mt-6">
                    <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=500&h=400&fit=crop&crop=faces" alt="Workshop session" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    {{-- Recent Jobs --}}
    @if ($recentJobs->isNotEmpty())
        <section class="py-24 bg-gradient-to-b from-white to-slate-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100/80 text-emerald-700 text-xs font-semibold ring-1 ring-inset ring-emerald-600/10 mb-4">Career</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Latest Opportunities</h2>
                    <p class="mt-4 text-gray-500 text-lg">Recent job openings shared by fellow alumni.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($recentJobs as $job)
                        <div class="bg-white rounded-2xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg hover:border-indigo-200/60 transition-all duration-300 group">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $job->title }}</h3>
                            @if ($job->salary)
                                <p class="text-sm text-gray-500 mb-3">{{ $job->salary }}</p>
                            @endif
                            @if ($job->tags->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach ($job->tags->take(3) as $tag)
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/10">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if ($job->expiry_date)
                                <p class="text-xs text-gray-400 mt-3">Expires {{ $job->expiry_date->format('M d, Y') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming Events --}}
    <section id="events" class="py-24 bg-white scroll-mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Event image --}}
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-2xl shadow-indigo-500/10">
                        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=700&h=500&fit=crop" alt="Alumni event" class="w-full h-80 object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[radial-gradient(circle,_#c7d2fe_1.5px,_transparent_1.5px)] bg-[length:10px_10px] opacity-40"></div>
                </div>

                {{-- Right: Events list --}}
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100/80 text-blue-700 text-xs font-semibold ring-1 ring-inset ring-blue-600/10 mb-4">Community</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
                    <p class="text-gray-500 text-lg mb-8">Don't miss out on what's happening in the community.</p>

                    @if ($upcomingEvents->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($upcomingEvents as $event)
                                <div class="bg-gradient-to-r from-slate-50 to-white rounded-2xl border border-gray-200/60 p-5 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start gap-4">
                                        @if ($event->event_date)
                                            <div class="shrink-0 w-14 h-14 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex flex-col items-center justify-center text-white shadow-md">
                                                <span class="text-xs font-medium leading-none">{{ $event->event_date->format('M') }}</span>
                                                <span class="text-lg font-bold leading-tight">{{ $event->event_date->format('d') }}</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h3 class="text-base font-semibold text-gray-900">{{ $event->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ Str::limit($event->body, 100) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-50 rounded-2xl border border-gray-200/60 p-8 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm text-gray-400">No upcoming events at the moment. Stay tuned!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Alumni Voice --}}
    <section id="alumni-voice" class="py-24 bg-gradient-to-b from-slate-50 to-indigo-50/30 scroll-mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100/80 text-indigo-700 text-xs font-semibold ring-1 ring-inset ring-indigo-600/10 mb-4">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Alumni Voice</h2>
                <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Hear from our alumni about how this network has impacted their careers and connections.</p>
            </div>

            {{-- Testimonial Slider --}}
            <div class="relative max-w-4xl mx-auto overflow-hidden" style="min-height: 280px;">
                @php
                    $testimonials = [
                        [
                            'message' => 'This alumni network helped me land my dream job at a leading tech company. The peer connections and job board made all the difference in my career transition.',
                            'name' => 'Farhan Ahmed',
                            'designation' => 'Senior Software Engineer',
                            'company' => 'TigerIT Bangladesh',
                            'batch' => '15th',
                            'intake' => 28,
                            'shift' => 'Day',
                            'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face',
                        ],
                        [
                            'message' => 'Being part of this verified community gave me access to mentors who guided me through the early stages of my startup. The trust-based network is invaluable.',
                            'name' => 'Tasnia Rahman',
                            'designation' => 'Co-founder & CTO',
                            'company' => 'CodeCraft BD',
                            'batch' => '12th',
                            'intake' => 22,
                            'shift' => 'Day',
                            'photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop&crop=face',
                        ],
                        [
                            'message' => 'After moving abroad, this platform kept me connected to my roots. I have referred several juniors for positions at my company through the network.',
                            'name' => 'Raihan Kabir',
                            'designation' => 'DevOps Lead',
                            'company' => 'Booking.com',
                            'batch' => '10th',
                            'intake' => 18,
                            'shift' => 'Evening',
                            'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face',
                        ],
                        [
                            'message' => 'The events organized through this platform brought our batch together after years. It feels great to reconnect and grow as a professional community.',
                            'name' => 'Nusrat Jahan',
                            'designation' => 'Product Manager',
                            'company' => 'bKash Limited',
                            'batch' => '18th',
                            'intake' => 34,
                            'shift' => 'Day',
                            'photo' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&h=200&fit=crop&crop=face',
                        ],
                    ];
                @endphp

                @foreach ($testimonials as $index => $testimonial)
                    <div class="testimonial-slide absolute inset-0 flex items-center justify-center opacity-0 animate-testimonial-fade" style="animation-delay: {{ $index * 5 }}s;">
                        <div class="bg-white rounded-3xl border border-gray-200/60 shadow-xl shadow-gray-900/5 p-8 sm:p-10 max-w-2xl mx-auto">
                            {{-- Quote icon --}}
                            <svg class="w-10 h-10 text-indigo-200 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>

                            <p class="text-gray-600 text-lg leading-relaxed mb-8 italic">"{{ $testimonial['message'] }}"</p>

                            <div class="flex items-center gap-4">
                                <img src="{{ $testimonial['photo'] }}" alt="{{ $testimonial['name'] }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 shadow-sm" loading="lazy">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                                    <p class="text-sm text-indigo-600 font-medium">{{ $testimonial['designation'] }}, {{ $testimonial['company'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $testimonial['batch'] }} Batch · Intake {{ $testimonial['intake'] }} · {{ $testimonial['shift'] }} Shift</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Dot indicators --}}
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 flex items-center gap-2">
                    @foreach ($testimonials as $index => $testimonial)
                        <span class="w-2 h-2 rounded-full bg-indigo-300/50 testimonial-dot" data-index="{{ $index }}"></span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Membership Plans --}}
    <section id="membership" class="py-24 bg-white scroll-mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100/80 text-amber-700 text-xs font-semibold ring-1 ring-inset ring-amber-600/10 mb-4">Plans</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Membership Plans</h2>
                <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Choose the plan that fits your journey. All plans include core access to the alumni network.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                {{-- Free Plan --}}
                <div class="bg-white rounded-2xl border border-gray-200/60 p-8 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Free</h3>
                        <p class="text-sm text-gray-500 mt-1">Get started with the basics</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">৳0</span>
                        <span class="text-gray-400 text-sm">/forever</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Alumni directory access
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Browse job board
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Event notifications
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Basic profile
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Post job listings
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full px-6 py-3 border-2 border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                        Get Started
                    </a>
                </div>

                {{-- Pro Plan (Highlighted) --}}
                <div class="bg-gradient-to-b from-indigo-600 to-violet-700 rounded-2xl p-8 shadow-2xl shadow-indigo-500/20 flex flex-col relative ring-2 ring-indigo-500/20 scale-[1.02]">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-400 text-amber-900 text-xs font-bold shadow-lg">Most Popular</span>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-white">Pro</h3>
                        <p class="text-sm text-indigo-200 mt-1">For active professionals</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-white">৳500</span>
                        <span class="text-indigo-200 text-sm">/year</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-indigo-100">
                            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Free
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-indigo-100">
                            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Post unlimited job listings
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-indigo-100">
                            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Featured profile badge
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-indigo-100">
                            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Priority event registration
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-indigo-100">
                            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tag-based job alerts
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full px-6 py-3 bg-white text-indigo-700 text-sm font-bold rounded-xl hover:bg-indigo-50 transition-all shadow-lg">
                        Join as Pro
                    </a>
                </div>

                {{-- Lifetime Plan --}}
                <div class="bg-white rounded-2xl border border-gray-200/60 p-8 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Lifetime</h3>
                        <p class="text-sm text-gray-500 mt-1">One-time, forever access</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">৳2,000</span>
                        <span class="text-gray-400 text-sm">/once</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Pro
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Lifetime access — no renewals
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Exclusive alumni badge
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            VIP event access
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Support the community fund
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full px-6 py-3 border-2 border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                        Go Lifetime
                    </a>
                </div>

                {{-- Honorary Membership --}}
                <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl border border-gray-200/60 p-8 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col relative overflow-hidden">
                    {{-- Decorative badge ribbon --}}
                    <div class="absolute top-0 right-0 w-20 h-20 overflow-hidden">
                        <div class="absolute top-3 -right-6 w-28 text-center text-[10px] font-bold text-white bg-gradient-to-r from-gray-700 to-gray-900 rotate-45 py-1 shadow-sm">INVITE</div>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Honorary</h3>
                        <p class="text-sm text-gray-500 mt-1">By invitation only</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">Free</span>
                        <span class="text-gray-400 text-sm">/invited</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Lifetime
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Honorary member badge
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Featured in alumni spotlight
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Advisory board access
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Granted by the board
                        </li>
                    </ul>
                    <span class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-100 text-gray-500 text-sm font-semibold rounded-xl cursor-default">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Invitation Only
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Banner --}}
    <section class="py-24 bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-700 relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Ready to reconnect with<br>your BUBT family?</h2>
            <p class="mt-5 text-indigo-100 text-lg">Join hundreds of alumni building a stronger professional network together.</p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-8 py-3.5 bg-white text-indigo-700 text-base font-bold rounded-xl hover:bg-indigo-50 transition-all shadow-xl shadow-indigo-900/20">
                    Register Now
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-8 py-3.5 text-white/90 text-base font-semibold rounded-xl hover:text-white hover:bg-white/10 transition-all border border-white/20">
                    Already a member? Sign In
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.testimonial-slide');
        const dots = document.querySelectorAll('.testimonial-dot');
        if (slides.length === 0) return;

        let currentIndex = 0;
        const interval = 5000;

        function showSlide(index) {
            slides.forEach(function (slide, i) {
                slide.style.opacity = i === index ? '1' : '0';
                slide.style.transform = i === index ? 'translateX(0)' : 'translateX(20px)';
                slide.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            dots.forEach(function (dot, i) {
                dot.style.backgroundColor = i === index ? '#6366f1' : 'rgba(165,180,252,0.5)';
            });
        }

        // Override CSS animation with JS for reliable control
        slides.forEach(function (slide) {
            slide.style.animation = 'none';
        });

        showSlide(0);
        setInterval(function () {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }, interval);
    });
</script>
@endpush
