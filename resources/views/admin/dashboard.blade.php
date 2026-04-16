@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview of the alumni platform.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Verified</p>
            <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($stats['verified_users']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($stats['pending_users']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Blocked</p>
            <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($stats['blocked_users']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Notices</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_notices']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Events</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_events']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Registrations</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_registrations']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Jobs</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Jobs</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($stats['pending_jobs']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Approved Jobs</p>
            <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($stats['approved_jobs']) }}</p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Manage Users
        </a>
        <a href="{{ route('notices.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            Notices
        </a>
        <a href="{{ route('admin.jobs.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Moderate Jobs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Users --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Users</h2>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentUsers as $user)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $user->role->value === 'admin' ? 'bg-red-50 text-red-700' : ($user->role->value === 'manager' ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700') }}">
                                        {{ $user->role->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($user->blocked_at)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700">Blocked</span>
                                    @elseif ($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700">Verified</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">Pending</span>
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
            </div>
        </div>

        {{-- Pending Jobs --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pending Jobs</h2>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Posted By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pendingJobs as $job)
                            <tr>
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
            </div>
        </div>
    </div>
@endsection
