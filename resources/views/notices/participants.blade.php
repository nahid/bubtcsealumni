@extends('layouts.app')

@section('title', 'Participants — ' . $notice->title)

@section('content')
    <div>
        <a href="{{ route('notices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Notices
        </a>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $notice->title }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $registrations->total() }} {{ Str::plural('participant', $registrations->total()) }} registered
                </p>
            </div>
            <a href="{{ route('notices.participants.export', $notice) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>

        @if ($registrations->isEmpty())
            <div class="text-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm">
                <svg class="mx-auto h-14 w-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-gray-900">No registrations yet</h3>
                <p class="mt-1 text-sm text-gray-500">No one has registered for this event yet.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
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
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($registrations as $registration)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $registration->user->name }}</td>
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
            </div>

            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
@endsection
