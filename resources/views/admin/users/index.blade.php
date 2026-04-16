@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <x-page-header title="User Management" subtitle="Manage alumni accounts, roles, and board positions." />

    {{-- Search & Filters --}}
    <x-card class="mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or alumni ID…"
                           class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div>
                    <select name="role" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="blocked" @selected(request('status') === 'blocked')>Blocked</option>
                    </select>
                    <x-button variant="primary" type="submit">Filter</x-button>
                </div>
            </div>
            @if (request('search') || request('role') || request('status'))
                <div class="mt-3">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">✕ Clear filters</a>
                </div>
            @endif
        </form>
    </x-card>

    {{-- Users Table --}}
    <x-card :padding="false" class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Alumni ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <x-avatar :name="$user->name" size="xs" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono whitespace-nowrap">{{ $user->alumni_id ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <x-badge :color="$user->role->value === 'admin' ? 'danger' : ($user->role->value === 'manager' ? 'warning' : 'success')" size="xs">
                                    {{ $user->role->label() }}
                                </x-badge>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($user->board_position)
                                    <x-badge color="primary" size="xs">{{ $user->board_position->label() }}</x-badge>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
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
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="rounded-xl border-gray-300 text-xs py-1 pl-2 pr-6 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                                                @endforeach
                                            </select>
                                            <x-button variant="ghost" size="xs" type="submit">Set</x-button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.position', $user) }}" class="flex items-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <select name="board_position" class="rounded-xl border-gray-300 text-xs py-1 pl-2 pr-6 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                            <option value="">None</option>
                                            @foreach ($positions as $position)
                                                <option value="{{ $position->value }}" @selected($user->board_position === $position)>{{ $position->label() }}</option>
                                            @endforeach
                                        </select>
                                        <x-button variant="ghost" size="xs" type="submit">Assign</x-button>
                                    </form>

                                    @if ($user->board_position)
                                        <form method="POST" action="{{ route('admin.users.position.remove', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-button variant="danger" size="xs" type="submit">Remove Position</x-button>
                                        </form>
                                    @endif

                                    @if ($user->id !== auth()->id())
                                        @if ($user->isBlocked())
                                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline">
                                                @csrf
                                                <x-button variant="success" size="xs" type="submit">Unblock</x-button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to block this user?')">
                                                @csrf
                                                <x-button variant="danger" size="xs" type="submit">Block</x-button>
                                            </form>
                                        @endif
                                    @endif

                                    @if ($user->status === 'pending')
                                        <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                                            @csrf
                                            <x-button variant="success" size="xs" type="submit">Verify</x-button>
                                        </form>
                                    @endif

                                    @if ($user->id === auth()->id())
                                        <span class="text-xs text-gray-400 italic">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
