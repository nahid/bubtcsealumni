@extends('layouts.app')

@section('title', 'Moderate Jobs')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Job Moderation</h1>
        <p class="text-sm text-gray-500 mt-1">Review and moderate job posts submitted by alumni.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('admin.jobs.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search jobs by title…"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex gap-2">
                <select name="filter" class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('filter') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('filter') === 'approved')>Approved</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Filter
                </button>
            </div>
        </div>
        @if (request('search') || request('filter'))
            <div class="mt-3">
                <a href="{{ route('admin.jobs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">✕ Clear filters</a>
            </div>
        @endif
    </form>

    {{-- Jobs Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
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
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ Str::limit($job->title, 40) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $job->user->name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($job->tags as $tag)
                                        <span class="inline-block px-2 py-0.5 rounded-md text-xs bg-gray-100 text-gray-600">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($job->approved_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700">Approved</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 whitespace-nowrap">{{ $job->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if (! $job->approved_at)
                                        <form method="POST" action="{{ route('admin.jobs.approve', $job) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.jobs.reject', $job) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-50 rounded-md hover:bg-amber-100 transition">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this job post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition">
                                            Delete
                                        </button>
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
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $jobPosts->links() }}
    </div>
@endsection
