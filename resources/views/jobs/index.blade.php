@extends('layouts.app')

@section('title', 'Job Board')

@section('content')
    <x-page-header title="Job Board" subtitle="Browse opportunities shared by fellow alumni">
        <x-button variant="primary" :href="route('jobs.create')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Post a Job
        </x-button>
    </x-page-header>

    {{-- Search & Filters --}}
    <x-card class="mb-6">
        <form method="GET" action="{{ route('jobs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs by title…"
                    class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
            <x-button variant="secondary" type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Search
            </x-button>
            @if(request('search') || request('tag'))
                <x-button variant="ghost" type="button" :href="route('jobs.index')">Clear</x-button>
            @endif
        </form>

        {{-- Tag Filter Pills --}}
        <div class="mt-4 flex flex-wrap gap-2" id="tag-filter">
            @foreach ($allTags as $tag)
                <a href="{{ route('jobs.index', ['tag' => $tag->slug, 'search' => request('search')]) }}"
                   class="tag-pill inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150
                          {{ request('tag') === $tag->slug ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 hover:border-gray-300' }}"
                   data-tag="{{ $tag->slug }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </x-card>

    {{-- Tag Subscriptions --}}
    <x-card class="mb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Your Tag Subscriptions</h3>
        <div class="flex flex-wrap gap-2" id="tag-subscriptions">
            @php $subscribedIds = auth()->user()->subscribedTags->pluck('id')->toArray(); @endphp
            @foreach ($allTags as $tag)
                <button
                    class="tag-subscribe-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150 cursor-pointer
                           {{ in_array($tag->id, $subscribedIds) ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100' }}"
                    data-tag-id="{{ $tag->id }}"
                    data-url="{{ route('tags.toggle', $tag) }}"
                    data-subscribed="{{ in_array($tag->id, $subscribedIds) ? '1' : '0' }}">
                    <span class="subscribe-icon">{{ in_array($tag->id, $subscribedIds) ? '✓' : '+' }}</span>
                    #{{ $tag->name }}
                </button>
            @endforeach
        </div>
    </x-card>

    {{-- Job Listings --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="job-listings">
        @forelse ($jobPosts as $jobPost)
            <x-card :hover="true" class="flex flex-col job-card" data-tags="{{ $jobPost->tags->pluck('slug')->implode(',') }}">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-900 leading-snug">
                        <a href="{{ route('jobs.show', $jobPost) }}" class="hover:text-indigo-600 transition-colors">
                            {{ $jobPost->title }}
                        </a>
                    </h3>
                    @if($jobPost->isOpen())
                        <x-badge color="success" class="ml-2 shrink-0">Open</x-badge>
                    @else
                        <x-badge color="danger" class="ml-2 shrink-0">Closed</x-badge>
                    @endif
                </div>

                <div class="flex items-center gap-2 mb-2">
                    <x-avatar :name="$jobPost->user->name" size="xs" />
                    <p class="text-xs text-gray-500">{{ $jobPost->user->name }}</p>
                </div>

                @if($jobPost->salary)
                    <p class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $jobPost->salary }}
                    </p>
                @endif

                <p class="text-xs text-gray-400 mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Expires {{ $jobPost->expiry_date->format('M d, Y') }}
                </p>

                <div class="flex flex-wrap gap-1.5 mt-auto pt-3 border-t border-gray-100">
                    @foreach ($jobPost->tags as $tag)
                        <x-badge color="neutral" size="xs">#{{ $tag->name }}</x-badge>
                    @endforeach
                </div>
            </x-card>
        @empty
            <div class="col-span-full">
                <x-card>
                    <x-empty-state icon="briefcase" title="No jobs posted yet" description="Be the first to share an opportunity with the alumni network!">
                        <x-button variant="primary" size="sm" :href="route('jobs.create')">Post a Job</x-button>
                    </x-empty-state>
                </x-card>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $jobPosts->links() }}
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Real-time client-side tag filtering (supplements server-side)
    $('#tag-filter').on('click', '.tag-pill', function (e) {
        // Let the link work normally for server-side filtering
    });

    // Tag subscription toggle (AJAX, no page refresh)
    $('#tag-subscriptions').on('click', '.tag-subscribe-btn', function () {
        const $btn = $(this);
        const url = $btn.data('url');

        $btn.prop('disabled', true).addClass('opacity-50');

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            dataType: 'json',
            success: function (data) {
                $btn.data('subscribed', data.subscribed ? '1' : '0');

                if (data.subscribed) {
                    $btn.removeClass('bg-gray-50 text-gray-500 border-gray-200')
                        .addClass('bg-indigo-100 text-indigo-700 border-indigo-300');
                    $btn.find('.subscribe-icon').text('✓');
                } else {
                    $btn.removeClass('bg-indigo-100 text-indigo-700 border-indigo-300')
                        .addClass('bg-gray-50 text-gray-500 border-gray-200');
                    $btn.find('.subscribe-icon').text('+');
                }
            },
            complete: function () {
                $btn.prop('disabled', false).removeClass('opacity-50');
            }
        });
    });
});
</script>
@endpush

