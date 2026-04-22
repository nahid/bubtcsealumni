@extends('layouts.app')

@section('title', 'Manage Tags')

@section('content')
    <x-page-header title="Tags" subtitle="Manage tags used across job posts and subscriptions.">
        <x-button variant="primary" :href="route('admin.tags.create')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Tag
        </x-button>
    </x-page-header>

    <x-card class="mb-6">
        <form method="GET" action="{{ route('admin.tags.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tags by name or slug…"
                    class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
            <x-button variant="secondary" type="submit">Search</x-button>
            @if(request('search'))
                <x-button variant="ghost" type="button" :href="route('admin.tags.index')">Clear</x-button>
            @endif
        </form>
    </x-card>

    <x-card :padding="false" class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Jobs</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Subscribers</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tags as $tag)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <x-badge color="primary">#{{ $tag->name }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono whitespace-nowrap">{{ $tag->slug }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $tag->job_posts_count }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $tag->subscribers_count }}</td>
                            <td class="px-4 py-3 text-sm text-gray-400 whitespace-nowrap">{{ $tag->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="ghost" size="xs" :href="route('admin.tags.edit', $tag)">Edit</x-button>
                                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Delete tag &quot;{{ $tag->name }}&quot;? This will remove it from all jobs and subscriptions.');">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" size="xs" type="submit">Delete</x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No tags found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">
        {{ $tags->links() }}
    </div>
@endsection
