@extends('layouts.app')

@section('title', 'Post a Job')

@section('content')
    <div class="max-w-2xl mx-auto">
        <x-page-header title="Post a Job" subtitle="Share a job opportunity with the alumni network">
            <x-button variant="ghost" size="sm" :href="route('jobs.index')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </x-button>
        </x-page-header>

        <x-card>
            <form method="POST" action="{{ route('jobs.store') }}" class="space-y-5">
                @csrf

                <x-input name="title" label="Job Title" :error="$errors->first('title')" value="{{ old('title') }}" required placeholder="e.g. Senior Laravel Developer" />

                <x-input name="external_link" label="Application Link" type="url" :error="$errors->first('external_link')" value="{{ old('external_link') }}" required placeholder="https://example.com/apply" />

                <div class="grid grid-cols-2 gap-4">
                    <x-input name="salary" label="Salary" :error="$errors->first('salary')" value="{{ old('salary') }}" placeholder="e.g. 80k-120k BDT" hint="Optional" />

                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1.5">Expiry Date</label>
                        <input id="expiry_date" type="date" name="expiry_date" value="{{ old('expiry_date') }}" required
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        @error('expiry_date')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1.5">Tags</label>
                    <select id="tags" name="tags[]" multiple="multiple" required
                            class="w-full rounded-xl border border-gray-300 text-sm">
                        @foreach ($existingTags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tags.*')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button variant="primary">Post Job</x-button>
                    <x-button variant="ghost" type="button" :href="route('jobs.index')">Cancel</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection

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

