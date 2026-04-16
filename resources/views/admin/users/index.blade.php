@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="text-sm text-gray-500 mt-1">Manage alumni accounts, roles, and board positions.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, or alumni ID…"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="role" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="status" class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="blocked" @selected(request('status') === 'blocked')>Blocked</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Filter
                </button>
            </div>
        </div>
        @if (request('search') || request('role') || request('status'))
            <div class="mt-3">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">✕ Clear filters</a>
            </div>
        @endif
    </form>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Alumni ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono whitespace-nowrap">{{ $user->alumni_id ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $user->role->value === 'admin' ? 'bg-red-50 text-red-700' : ($user->role->value === 'manager' ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700') }}">
                                    {{ $user->role->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($user->board_position)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ $user->board_position->label() }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
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
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    {{-- Change Role --}}
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="rounded-md border-gray-300 text-xs py-1 pl-2 pr-6 focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">Set</button>
                                        </form>
                                    @endif

                                    {{-- Assign Position --}}
                                    <form method="POST" action="{{ route('admin.users.position', $user) }}" class="flex items-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <select name="board_position" class="rounded-md border-gray-300 text-xs py-1 pl-2 pr-6 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">None</option>
                                            @foreach ($positions as $position)
                                                <option value="{{ $position->value }}" @selected($user->board_position === $position)>{{ $position->label() }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">Assign</button>
                                    </form>

                                    {{-- Remove Position --}}
                                    @if ($user->board_position)
                                        <form method="POST" action="{{ route('admin.users.position.remove', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition">
                                                Remove Position
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Block / Unblock --}}
                                    @if ($user->id !== auth()->id())
                                        @if ($user->isBlocked())
                                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition">
                                                    Unblock
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to block this user?')">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition">
                                                    Block
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- Verify --}}
                                    @if ($user->status === 'pending')
                                        <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition">
                                                Verify
                                            </button>
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
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
