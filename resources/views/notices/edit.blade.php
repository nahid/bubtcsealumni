@extends('layouts.app')

@section('title', 'Edit Notice')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('notices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-6 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Notices
        </a>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Notice</h1>
                <p class="mt-1 text-sm text-gray-500">Last updated {{ $notice->updated_at->diffForHumans() }}</p>
            </div>
            @if ($notice->is_published)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/10">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    Published
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/10">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    Draft
                </span>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <form method="POST" action="{{ route('notices.update', $notice) }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $notice->title) }}"
                           required
                           placeholder="Enter a clear, descriptive title…"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type & Event Date Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Type</label>
                        <select name="type"
                                id="type"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="notice" @selected(old('type', $notice->type) === 'notice')>📢 Notice</option>
                            <option value="event" @selected(old('type', $notice->type) === 'event')>📅 Event</option>
                        </select>
                        @error('type')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event Date --}}
                    <div id="event-date-group">
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1.5">Event Date</label>
                        <input type="date"
                               name="event_date"
                               id="event_date"
                               value="{{ old('event_date', $notice->event_date?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('event_date')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Body --}}
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1.5">Content</label>
                    <textarea name="body"
                              id="body"
                              rows="8"
                              required
                              maxlength="5000"
                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400">{{ old('body', $notice->body) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Maximum 5,000 characters</p>
                    @error('body')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Published Toggle --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               {{ old('is_published', $notice->is_published) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Published</span>
                            <p class="text-xs text-gray-500 mt-0.5">When unchecked, the notice will be saved as a draft and hidden from alumni.</p>
                        </div>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Update Notice
                        </button>
                        <a href="{{ route('notices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </a>
                    </div>
                    <form method="POST" action="{{ route('notices.destroy', $notice) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
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

