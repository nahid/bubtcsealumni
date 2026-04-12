@extends('layouts.app')

@section('title', 'Job Board')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Job Board</h1>
            <p class="text-sm text-gray-500 mt-1">Browse opportunities shared by fellow alumni</p>
        </div>
        <a href="{{ route('jobs.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            + Post a Job
        </a>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('jobs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs by title…"
                   class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            <button type="submit"
                    class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                Search
            </button>
            @if(request('search') || request('tag'))
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 self-center">Clear</a>
            @endif
        </form>

        {{-- Tag Filter Pills --}}
        <div class="mt-4 flex flex-wrap gap-2" id="tag-filter">
            @foreach ($allTags as $tag)
                <a href="{{ route('jobs.index', ['tag' => $tag->slug, 'search' => request('search')]) }}"
                   class="tag-pill inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border transition
                          {{ request('tag') === $tag->slug ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}"
                   data-tag="{{ $tag->slug }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Tag Subscriptions --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Your Tag Subscriptions</h3>
        <div class="flex flex-wrap gap-2" id="tag-subscriptions">
            @php $subscribedIds = auth()->user()->subscribedTags->pluck('id')->toArray(); @endphp
            @foreach ($allTags as $tag)
                <button
                    class="tag-subscribe-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border transition
                           {{ in_array($tag->id, $subscribedIds) ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100' }}"
                    data-tag-id="{{ $tag->id }}"
                    data-url="{{ route('tags.toggle', $tag) }}"
                    data-subscribed="{{ in_array($tag->id, $subscribedIds) ? '1' : '0' }}">
                    <span class="subscribe-icon">{{ in_array($tag->id, $subscribedIds) ? '✓' : '+' }}</span>
                    #{{ $tag->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Job Listings --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="job-listings">
        @forelse ($jobPosts as $jobPost)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col job-card"
                 data-tags="{{ $jobPost->tags->pluck('slug')->implode(',') }}">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-900 leading-snug">
                        <a href="{{ route('jobs.show', $jobPost) }}" class="hover:text-indigo-600 transition">
                            {{ $jobPost->title }}
                        </a>
                    </h3>
                    @if($jobPost->isOpen())
                        <span class="shrink-0 ml-2 inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700">Open</span>
                    @else
                        <span class="shrink-0 ml-2 inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-600">Closed</span>
                    @endif
                </div>

                <p class="text-xs text-gray-500 mb-1">Posted by {{ $jobPost->user->name }}</p>

                @if($jobPost->salary)
                    <p class="text-sm font-medium text-gray-700 mb-2">💰 {{ $jobPost->salary }}</p>
                @endif

                <p class="text-xs text-gray-400 mb-3">Expires {{ $jobPost->expiry_date->format('M d, Y') }}</p>

                <div class="flex flex-wrap gap-1.5 mt-auto">
                    @foreach ($jobPost->tags as $tag)
                        <span class="inline-block px-2 py-0.5 rounded-md text-xs bg-gray-100 text-gray-600">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center text-sm text-gray-400">
                No job posts found. Be the first to share an opportunity!
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
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

