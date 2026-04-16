@extends('layouts.app')

@section('title', 'Participants — ' . $notice->title)

@section('content')
    <x-page-header :title="$notice->title" :subtitle="$registrations->total() . ' ' . Str::plural('participant', $registrations->total()) . ' registered'">
        <div class="flex items-center gap-2">
            <x-button variant="primary" size="sm" :href="route('notices.participants.export', $notice)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </x-button>
            <x-button variant="ghost" size="sm" :href="route('notices.index')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </x-button>
        </div>
    </x-page-header>

    @if ($registrations->isEmpty())
        <x-card>
            <x-empty-state icon="users" title="No registrations yet" description="No one has registered for this event yet." />
        </x-card>
    @else
        <x-card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumni ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            @if ($notice->form_schema)
                                @foreach ($notice->form_schema as $field)
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $field['label'] }}</th>
                                @endforeach
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($registrations as $registration)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <x-avatar :name="$registration->user->name" size="xs" />
                                        <span class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap font-mono">{{ $registration->user->alumni_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $registration->user->email }}</td>
                                @if ($notice->form_schema)
                                    @foreach ($notice->form_schema as $field)
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            @php
                                                $entry = $registration->form_data[$field['key']] ?? [];
                                                $value = $entry['value'] ?? '—';
                                            @endphp
                                            @if (is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value ?: '—' }}
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $registration->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">
            {{ $registrations->links() }}
        </div>
    @endif
@endsection
