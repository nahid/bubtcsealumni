@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <x-page-header title="User Management" subtitle="Manage alumni accounts, roles, and board positions.">
        <a href="{{ route('admin.users.create') }}">
            <x-button variant="primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Alumni User
            </x-button>
        </a>
    </x-page-header>

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

    {{-- Results summary --}}
    <p class="text-sm text-gray-500 mb-4">{{ $users->total() }} {{ Str::plural('user', $users->total()) }} found</p>

    {{-- User Cards --}}
    @forelse ($users as $user)
        <x-card :hover="true" class="mb-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- User Info --}}
                <div class="flex items-center gap-3 sm:w-1/3 min-w-0">
                    <x-avatar :name="$user->name" size="md" />
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profile.show', $user) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition-colors truncate">{{ $user->name }}</a>
                            @if ($user->id === auth()->id())
                                <x-badge color="neutral" size="xs">You</x-badge>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        @if ($user->alumni_id)
                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $user->alumni_id }}</p>
                        @endif
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-2 sm:w-1/4">
                    <x-badge :color="$user->role->value === 'admin' ? 'danger' : ($user->role->value === 'manager' ? 'warning' : 'neutral')" size="xs">
                        {{ $user->role->label() }}
                    </x-badge>
                    @if ($user->board_position)
                        <x-badge color="primary" size="xs">{{ $user->board_position->label() }}</x-badge>
                    @endif
                    @if ($user->blocked_at)
                        <x-badge color="danger" size="xs">Blocked</x-badge>
                    @elseif ($user->status === 'verified')
                        <x-badge color="success" size="xs">Verified</x-badge>
                    @else
                        <x-badge color="warning" size="xs">Pending</x-badge>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap items-center gap-2 sm:ml-auto">
                    @if ($user->id !== auth()->id())
                        {{-- Role --}}
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-1">
                            @csrf
                            @method('PUT')
                            <select name="role" class="rounded-xl border-gray-300 text-xs py-1.5 pl-2.5 pr-7 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                                @endforeach
                            </select>
                            <x-button variant="ghost" size="xs" type="submit">Set</x-button>
                        </form>

                        <span class="hidden sm:block w-px h-5 bg-gray-200"></span>

                        {{-- Quick Actions --}}
                        @if ($user->status === 'pending')
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                                @csrf
                                <x-button variant="success" size="xs" type="submit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Verify
                                </x-button>
                            </form>
                        @endif

                        @if ($user->isBlocked())
                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline">
                                @csrf
                                <x-button variant="success" size="xs" type="submit">Unblock</x-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="inline" onsubmit="return confirm('Block {{ $user->name }}?')">
                                @csrf
                                <x-button variant="danger" size="xs" type="submit">Block</x-button>
                            </form>
                        @endif
                    @else
                        <span class="text-xs text-gray-400 italic">Your account</span>
                    @endif
                </div>
            </div>

            {{-- Board Position Row (separate for clarity) --}}
            @if ($user->id !== auth()->id())
                <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <span class="text-xs text-gray-500 font-medium">Board Position:</span>
                    <form method="POST" action="{{ route('admin.users.position', $user) }}" class="flex items-center gap-1">
                        @csrf
                        @method('PUT')
                        <select name="board_position" class="rounded-xl border-gray-300 text-xs py-1.5 pl-2.5 pr-7 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
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
                            <x-button variant="ghost" size="xs" type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50">Remove</x-button>
                        </form>
                    @endif
                </div>
            @endif
        </x-card>
    @empty
        <x-card>
            <x-empty-state icon="users" title="No users found" description="Try adjusting your search or filter criteria." />
        </x-card>
    @endforelse

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
