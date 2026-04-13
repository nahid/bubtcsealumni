@extends('layouts.app')

@section('title', 'Post a Job')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Post a Job</h1>
            <p class="text-sm text-gray-500 mt-1">Share a job opportunity with the alumni network</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
            <form method="POST" action="{{ route('jobs.store') }}" class="space-y-5">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                           placeholder="e.g. Senior Laravel Developer">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- External Link --}}
                <div>
                    <label for="external_link" class="block text-sm font-medium text-gray-700 mb-1">Application Link</label>
                    <input id="external_link" type="url" name="external_link" value="{{ old('external_link') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                           placeholder="https://example.com/apply">
                    @error('external_link')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary & Expiry --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary <span class="text-gray-400">(optional)</span></label>
                        <input id="salary" type="text" name="salary" value="{{ old('salary') }}"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                               placeholder="e.g. 80k-120k BDT">
                        @error('salary')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                        <input id="expiry_date" type="date" name="expiry_date" value="{{ old('expiry_date') }}" required
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        @error('expiry_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                    <select id="tags" name="tags[]" multiple="multiple" required
                            class="w-full rounded-lg border border-gray-300 text-sm">
                        @foreach ($existingTags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tags.*')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Post Job
                    </button>
                    <a href="{{ route('jobs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.25rem 0.25rem;
        min-height: 42px;
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #4338ca;
        border-radius: 0.375rem;
        padding: 2px 8px;
        font-size: 0.75rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #6366f1;
        margin-right: 4px;
    }
    .select2-dropdown {
        border-radius: 0.5rem;
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    $('#tags').select2({
        placeholder: 'Select tags for this job…',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush

