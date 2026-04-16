@extends('layouts.app')

@section('title', $notice->title)

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>

        {{-- Event Details --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/10">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Event
                </span>
                @if ($notice->event_date)
                    <span class="text-xs text-indigo-600 font-medium">📅 {{ $notice->event_date->format('M d, Y') }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $notice->title }}</h1>
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $notice->body }}</div>
        </div>

        {{-- Already Registered --}}
        @if ($registration)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <h2 class="text-lg font-semibold text-green-700">Already Registered ✓</h2>
                </div>
                <p class="text-sm text-gray-500 mb-4">You registered on {{ $registration->created_at->format('M d, Y \a\t h:i A') }}.</p>

                @if ($notice->form_schema && $registration->form_data)
                    <div class="space-y-3 border-t border-gray-100 pt-4">
                        @foreach ($notice->form_schema as $field)
                            <div>
                                <span class="block text-xs font-medium text-gray-500">{{ $field['label'] }}</span>
                                <span class="text-sm text-gray-900">
                                    @php
                                        $entry = $registration->form_data[$field['key']] ?? [];
                                        $value = $entry['value'] ?? '—';
                                    @endphp
                                    @if (is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value ?: '—' }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        {{-- Registration Form --}}
        @elseif ($notice->hasRegistrationForm())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Register for this Event</h2>
                <p class="text-sm text-gray-500 mb-5">Fill out the form below to register.</p>

                {{-- Feedback --}}
                <div id="registration-feedback" class="hidden mb-4 rounded-lg p-4 text-sm"></div>

                <form id="registration-form" class="space-y-5">
                    @csrf
                    @foreach ($notice->form_schema as $field)
                        <div>
                            <label for="field-{{ $field['key'] }}" class="block text-sm font-medium text-gray-700 mb-1.5">
                                {{ $field['label'] }}
                                @if (!empty($field['required']))
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if ($field['type'] === 'text')
                                <input type="text"
                                       name="{{ $field['key'] }}"
                                       id="field-{{ $field['key'] }}"
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       {{ !empty($field['required']) ? 'required' : '' }}
                                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400">

                            @elseif ($field['type'] === 'textarea')
                                <textarea name="{{ $field['key'] }}"
                                          id="field-{{ $field['key'] }}"
                                          rows="4"
                                          placeholder="{{ $field['placeholder'] ?? '' }}"
                                          {{ !empty($field['required']) ? 'required' : '' }}
                                          class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400"></textarea>

                            @elseif ($field['type'] === 'select')
                                <select name="{{ $field['key'] }}"
                                        id="field-{{ $field['key'] }}"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">— Select —</option>
                                    @foreach ($field['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>

                            @elseif ($field['type'] === 'radio')
                                <div class="space-y-2 mt-1">
                                    @foreach ($field['options'] ?? [] as $option)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio"
                                                   name="{{ $field['key'] }}"
                                                   value="{{ $option }}"
                                                   {{ !empty($field['required']) ? 'required' : '' }}
                                                   class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif ($field['type'] === 'checkbox')
                                <div class="space-y-2 mt-1">
                                    @foreach ($field['options'] ?? [] as $option)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                   name="{{ $field['key'] }}[]"
                                                   value="{{ $option }}"
                                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <p class="hidden mt-1.5 text-xs text-red-600" id="error-{{ $field['key'] }}"></p>
                        </div>
                    @endforeach

                    <div class="pt-2">
                        <button type="submit"
                                id="register-submit"
                                class="w-full px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm disabled:opacity-50">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#registration-form').on('submit', function (e) {
        e.preventDefault();

        var $btn = $('#register-submit');
        var $feedback = $('#registration-feedback');

        // Clear previous errors
        $('[id^="error-"]').addClass('hidden').text('');
        $feedback.addClass('hidden');

        $btn.prop('disabled', true).text('Registering…');

        var formData = {};
        $(this).serializeArray().forEach(function (item) {
            // Handle checkbox arrays (name ends with [])
            if (item.name.endsWith('[]')) {
                var key = item.name.slice(0, -2);
                if (!formData[key]) {
                    formData[key] = [];
                }
                formData[key].push(item.value);
            } else if (item.name !== '_token') {
                formData[item.name] = item.value;
            }
        });

        $.ajax({
            url: '{{ route('events.register', $notice) }}',
            method: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (data) {
                $feedback
                    .removeClass('hidden bg-red-50 text-red-700 border-red-200')
                    .addClass('bg-green-50 text-green-700 border border-green-200')
                    .text(data.message);
                $('#registration-form').find('input, select, textarea, button').prop('disabled', true);
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (field) {
                            $('#error-' + field).removeClass('hidden').text(errors[field][0]);
                        });
                    }
                    if (xhr.responseJSON.message) {
                        $feedback
                            .removeClass('hidden bg-green-50 text-green-700 border-green-200')
                            .addClass('bg-red-50 text-red-700 border border-red-200')
                            .text(xhr.responseJSON.message);
                    }
                } else {
                    $feedback
                        .removeClass('hidden bg-green-50 text-green-700 border-green-200')
                        .addClass('bg-red-50 text-red-700 border border-red-200')
                        .text('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false).text('Register');
            }
        });
    });
});
</script>
@endpush
