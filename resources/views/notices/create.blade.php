@extends('layouts.app')

@section('title', 'Create Notice')

@section('content')
    <div class="max-w-2xl mx-auto">
        <x-page-header title="Create Notice" subtitle="Publish a notice or event for all alumni to see.">
            <x-button variant="ghost" size="sm" :href="route('notices.index')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </x-button>
        </x-page-header>

        <x-card>
            <form method="POST" action="{{ route('notices.store') }}" class="space-y-5">
                @csrf

                <x-input name="title" label="Title" :error="$errors->first('title')" value="{{ old('title') }}" required placeholder="Enter a clear, descriptive title…" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Type</label>
                        <select name="type" id="type" required
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="notice" @selected(old('type') === 'notice')>📢 Notice</option>
                            <option value="event" @selected(old('type') === 'event')>📅 Event</option>
                        </select>
                        @error('type')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="event-date-group">
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1.5">Event Date</label>
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        @error('event_date')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Registration Form Builder (events only) --}}
                <div id="form-builder-section" style="display: none;">
                    <div class="rounded-xl border border-gray-200 bg-gray-50/80 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Registration Form Fields</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Add custom fields for the event registration form.</p>
                            </div>
                            <x-button variant="ghost" size="xs" type="button" id="add-field-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Field
                            </x-button>
                        </div>
                        <div id="form-fields" class="space-y-3"></div>
                        <p id="no-fields-msg" class="text-xs text-gray-400 text-center py-4">No fields added yet. Click "Add Field" to start building your form.</p>
                    </div>
                    <input type="hidden" name="form_schema" id="form-schema-input" value="{{ old('form_schema', '[]') }}">
                    @error('form_schema')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-textarea name="body" label="Content" :error="$errors->first('body')" rows="8" required maxlength="5000" placeholder="Write your notice content…" hint="Maximum 5,000 characters">{{ old('body') }}</x-textarea>

                {{-- Published Toggle --}}
                <div class="rounded-xl bg-gray-50/80 border border-gray-100 p-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1"
                               {{ old('is_published', true) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Publish immediately</span>
                            <p class="text-xs text-gray-500 mt-0.5">When unchecked, the notice will be saved as a draft.</p>
                        </div>
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button variant="primary">Create Notice</x-button>
                    <x-button variant="ghost" type="button" :href="route('notices.index')">Cancel</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    function toggleEventDate() {
        var isEvent = $('#type').val() === 'event';
        $('#event-date-group').toggle(isEvent);
        $('#form-builder-section').toggle(isEvent);
        if (!isEvent) {
            $('#event_date').val('');
        }
    }

    $('#type').on('change', toggleEventDate);
    toggleEventDate();

    // Form Builder
    var fieldCounter = 0;

    function fieldRowHtml(counter, data) {
        data = data || {};
        var type = data.type || 'text';
        var label = data.label || '';
        var placeholder = data.placeholder || '';
        var options = (data.options || []).join('\n');
        var required = data.required ? 'checked' : '';
        var showPlaceholder = (type === 'text' || type === 'textarea') ? '' : 'display:none;';
        var showOptions = (type === 'select' || type === 'radio' || type === 'checkbox') ? '' : 'display:none;';

        return '<div class="field-row bg-white rounded-lg border border-gray-200 p-4" data-index="' + counter + '">' +
            '<div class="flex items-start gap-3">' +
                '<div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">' +
                    '<div>' +
                        '<label class="block text-xs font-medium text-gray-500 mb-1">Field Type</label>' +
                        '<select class="field-type w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">' +
                            '<option value="text"' + (type === 'text' ? ' selected' : '') + '>Text</option>' +
                            '<option value="textarea"' + (type === 'textarea' ? ' selected' : '') + '>Textarea</option>' +
                            '<option value="select"' + (type === 'select' ? ' selected' : '') + '>Select</option>' +
                            '<option value="radio"' + (type === 'radio' ? ' selected' : '') + '>Radio</option>' +
                            '<option value="checkbox"' + (type === 'checkbox' ? ' selected' : '') + '>Checkbox</option>' +
                        '</select>' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-xs font-medium text-gray-500 mb-1">Label</label>' +
                        '<input type="text" class="field-label w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400" placeholder="e.g. T-Shirt Size" value="' + $('<span>').text(label).html() + '">' +
                    '</div>' +
                    '<div class="field-placeholder-wrap" style="' + showPlaceholder + '">' +
                        '<label class="block text-xs font-medium text-gray-500 mb-1">Placeholder</label>' +
                        '<input type="text" class="field-placeholder w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400" placeholder="Optional placeholder text" value="' + $('<span>').text(placeholder).html() + '">' +
                    '</div>' +
                    '<div class="field-options-wrap" style="' + showOptions + '">' +
                        '<label class="block text-xs font-medium text-gray-500 mb-1">Options <span class="text-gray-400 font-normal">(one per line)</span></label>' +
                        '<textarea class="field-options w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400" rows="3" placeholder="Option 1&#10;Option 2&#10;Option 3">' + $('<span>').text(options).html() + '</textarea>' +
                    '</div>' +
                    '<div class="sm:col-span-2">' +
                        '<label class="flex items-center gap-2 cursor-pointer">' +
                            '<input type="checkbox" class="field-required h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" ' + required + '>' +
                            '<span class="text-sm text-gray-700">Required</span>' +
                        '</label>' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="remove-field-btn mt-5 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Remove field">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>' +
            '</div>' +
        '</div>';
    }

    function updateNoFieldsMsg() {
        $('#no-fields-msg').toggle($('#form-fields .field-row').length === 0);
    }

    function serializeFields() {
        var fields = [];
        $('#form-fields .field-row').each(function (i) {
            var $row = $(this);
            var type = $row.find('.field-type').val();
            var field = {
                key: 'field_' + (i + 1),
                type: type,
                label: $row.find('.field-label').val().trim(),
                required: $row.find('.field-required').is(':checked')
            };
            if (type === 'text' || type === 'textarea') {
                var ph = $row.find('.field-placeholder').val().trim();
                if (ph) field.placeholder = ph;
            }
            if (type === 'select' || type === 'radio' || type === 'checkbox') {
                field.options = $row.find('.field-options').val().split('\n').map(function (o) { return o.trim(); }).filter(Boolean);
            }
            if (field.label) fields.push(field);
        });
        $('#form-schema-input').val(JSON.stringify(fields));
    }

    $('#add-field-btn').on('click', function () {
        fieldCounter++;
        $('#form-fields').append(fieldRowHtml(fieldCounter));
        updateNoFieldsMsg();
    });

    $('#form-fields').on('click', '.remove-field-btn', function () {
        $(this).closest('.field-row').remove();
        updateNoFieldsMsg();
    });

    $('#form-fields').on('change', '.field-type', function () {
        var $row = $(this).closest('.field-row');
        var type = $(this).val();
        $row.find('.field-placeholder-wrap').toggle(type === 'text' || type === 'textarea');
        $row.find('.field-options-wrap').toggle(type === 'select' || type === 'radio' || type === 'checkbox');
    });

    // Serialize before form submit
    $('form').on('submit', function () {
        serializeFields();
    });

    // Load existing fields from old input
    var existingSchema = [];
    try {
        existingSchema = JSON.parse($('#form-schema-input').val());
    } catch (e) {}
    if (Array.isArray(existingSchema)) {
        existingSchema.forEach(function (field) {
            fieldCounter++;
            $('#form-fields').append(fieldRowHtml(fieldCounter, field));
        });
    }
    updateNoFieldsMsg();
});
</script>
@endpush

