@extends('layouts.app')

@section('title', 'Moderate Jobs')

@section('content')
    <x-page-header title="Job Moderation" subtitle="Review and moderate job posts submitted by alumni." />

    {{-- Search & Filters --}}
    <x-card class="mb-6">
        <form method="GET" action="{{ route('admin.jobs.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs by title…"
                           class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div class="flex gap-2">
                    <select name="filter" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(request('filter') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('filter') === 'approved')>Approved</option>
                    </select>
                    <x-button variant="primary" type="submit">Filter</x-button>
                </div>
            </div>
            @if (request('search') || request('filter'))
                <div class="mt-3">
                    <a href="{{ route('admin.jobs.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">✕ Clear filters</a>
                </div>
            @endif
        </form>
    </x-card>

    {{-- Jobs Table --}}
    <x-card :padding="false" class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Posted By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Tags</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($jobPosts as $job)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ Str::limit($job->title, 40) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <x-avatar :name="$job->user->name" size="xs" />
                                    <span class="text-sm text-gray-500">{{ $job->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($job->tags as $tag)
                                        <x-badge color="neutral" size="xs">#{{ $tag->name }}</x-badge>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($job->approved_at)
                                    <x-badge color="success" size="xs">Approved</x-badge>
                                @else
                                    <x-badge color="warning" size="xs">Pending</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 whitespace-nowrap">{{ $job->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if (! $job->approved_at)
                                        <form method="POST" action="{{ route('admin.jobs.approve', $job) }}" class="inline">
                                            @csrf
                                            <x-button variant="success" size="xs" type="submit">Approve</x-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.jobs.reject', $job) }}" class="inline">
                                            @csrf
                                            <x-button variant="ghost" size="xs" type="submit">Reject</x-button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this job post?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" size="xs" type="submit">Delete</x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No job posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">
        {{ $jobPosts->links() }}
    </div>
@endsection
