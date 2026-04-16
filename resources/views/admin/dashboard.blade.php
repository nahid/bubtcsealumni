@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <x-page-header title="Admin Dashboard" subtitle="Overview of the alumni platform." />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <x-stat-card :number="$stats['total_users']" label="Total Users" color="primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['verified_users']" label="Verified" color="success">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['pending_users']" label="Pending" color="warning">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['blocked_users']" label="Blocked" color="danger">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['total_notices']" label="Notices" color="primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['total_events']" label="Events" color="info">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['total_registrations']" label="Registrations" color="success">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['total_jobs']" label="Total Jobs" color="primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['pending_jobs']" label="Pending Jobs" color="warning">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-stat-card>
        <x-stat-card :number="$stats['approved_jobs']" label="Approved Jobs" color="success">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </x-stat-card>
    </div>

    {{-- Quick Links --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <x-button variant="primary" :href="route('admin.users.index')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Manage Users
        </x-button>
        <x-button variant="secondary" :href="route('notices.index')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Notices
        </x-button>
        <x-button variant="secondary" :href="route('admin.jobs.index')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Moderate Jobs
        </x-button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Users --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Recent Users</h2>
            <x-card :padding="false" class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentUsers as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <x-avatar :name="$user->name" size="xs" />
                                        <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <x-badge :color="$user->role->value === 'admin' ? 'danger' : ($user->role->value === 'manager' ? 'warning' : 'success')" size="xs">
                                        {{ $user->role->label() }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($user->blocked_at)
                                        <x-badge color="danger" size="xs">Blocked</x-badge>
                                    @elseif ($user->email_verified_at)
                                        <x-badge color="success" size="xs">Verified</x-badge>
                                    @else
                                        <x-badge color="warning" size="xs">Pending</x-badge>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-400">No users yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>

        {{-- Pending Jobs --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Pending Jobs</h2>
            <x-card :padding="false" class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Posted By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pendingJobs as $job)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ Str::limit($job->title, 30) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $job->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-400 whitespace-nowrap">{{ $job->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-400">No pending jobs.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
@endsection
