@extends('layouts.app')

@section('title', 'Create Notice')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('notices.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Notices
        </a>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Notice</h1>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('notices.store') }}">
                @csrf

                {{-- Title --}}
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div class="mb-5">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type"
                            id="type"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="notice" @selected(old('type') === 'notice')>Notice</option>
                        <option value="event" @selected(old('type') === 'event')>Event</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Event Date --}}
                <div class="mb-5" id="event-date-group">
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                    <input type="date"
                           name="event_date"
                           id="event_date"
                           value="{{ old('event_date') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('event_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Body --}}
                <div class="mb-5">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="body"
                              id="body"
                              rows="6"
                              required
                              maxlength="5000"
                              placeholder="Write your notice content…"
                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Published --}}
                <div class="mb-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               {{ old('is_published', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Publish immediately</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Create Notice
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    function toggleEventDate() {
        const isEvent = $('#type').val() === 'event';
        $('#event-date-group').toggle(isEvent);
        if (!isEvent) {
            $('#event_date').val('');
        }
    }

    $('#type').on('change', toggleEventDate);
    toggleEventDate();
});
</script>
@endpush

