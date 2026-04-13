@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            {{-- Success/Error Feedback --}}
            <div id="profile-feedback" class="hidden mb-4 rounded-lg p-4 text-sm"></div>

            <form id="profile-form" enctype="multipart/form-data">
                {{-- Profile Photo --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <div id="photo-preview">
                            @if ($user->profile_photo)
                                <img src="{{ Storage::disk('public')->url($user->profile_photo) }}"
                                     alt="{{ $user->name }}"
                                     class="h-20 w-20 rounded-full object-cover border-2 border-indigo-100">
                            @else
                                <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file"
                                   name="profile_photo"
                                   id="profile-photo-input"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="block text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-400">JPG, PNG, or WebP. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ $user->name }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="hidden mt-1 text-xs text-red-600" id="error-name"></p>
                </div>

                {{-- Bio --}}
                <div class="mb-5">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea name="bio"
                              id="bio"
                              rows="4"
                              maxlength="1000"
                              placeholder="Tell the community about yourself…"
                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $user->bio }}</textarea>
                    <p class="mt-1 text-xs text-gray-400"><span id="bio-count">{{ strlen($user->bio ?? '') }}</span>/1000</p>
                    <p class="hidden mt-1 text-xs text-red-600" id="error-bio"></p>
                </div>

                {{-- WhatsApp Number --}}
                <div class="mb-6">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                    <input type="text"
                           name="whatsapp_number"
                           id="whatsapp_number"
                           value="{{ $user->whatsapp_number }}"
                           placeholder="+8801XXXXXXXXX"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-400">Include country code for WhatsApp link to work correctly.</p>
                    <p class="hidden mt-1 text-xs text-red-600" id="error-whatsapp_number"></p>
                </div>

                {{-- Tag Subscriptions --}}
                <div class="mb-6">
                    <label for="subscribed_tags" class="block text-sm font-medium text-gray-700 mb-1">Subscribed Tags</label>
                    <select name="subscribed_tags[]"
                            id="subscribed_tags"
                            multiple="multiple"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        @foreach ($allTags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, $subscribedTagIds) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Follow tags to get notified when matching jobs are posted.</p>
                    <p class="hidden mt-1 text-xs text-red-600" id="error-subscribed_tags"></p>
                </div>

                {{-- Read-only Info --}}
                <div class="mb-6 grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Email</span>
                        <span class="text-sm text-gray-700">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Mobile</span>
                        <span class="text-sm text-gray-700">{{ $user->mobile }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Intake</span>
                        <span class="text-sm text-gray-700">{{ $user->intake }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Shift</span>
                        <span class="text-sm text-gray-700 capitalize">{{ $user->shift }}</span>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        id="profile-submit"
                        class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- View Public Profile Link --}}
        <div class="mt-4 text-center">
            <a href="{{ route('profile.show', $user) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                View your public profile →
            </a>
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
    // Initialize Select2 for tag subscriptions
    $('#subscribed_tags').select2({
        placeholder: 'Select tags to follow…',
        allowClear: true,
        width: '100%'
    });

    // Bio character counter
    $('#bio').on('input', function () {
        $('#bio-count').text($(this).val().length);
    });

    // Photo preview
    $('#profile-photo-input').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#photo-preview').html(
                    '<img src="' + e.target.result + '" alt="Preview" class="h-20 w-20 rounded-full object-cover border-2 border-indigo-100">'
                );
            };
            reader.readAsDataURL(file);
        }
    });

    // AJAX form submission
    $('#profile-form').on('submit', function (e) {
        e.preventDefault();

        const $btn = $('#profile-submit');
        const $feedback = $('#profile-feedback');

        // Clear previous errors
        $('.text-red-600').addClass('hidden').text('');
        $feedback.addClass('hidden');

        $btn.prop('disabled', true).text('Saving…');

        const formData = new FormData(this);

        $.ajax({
            url: '{{ route('profile.update') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (data) {
                $feedback
                    .removeClass('hidden bg-red-50 text-red-700 border-red-200')
                    .addClass('bg-green-50 text-green-700 border border-green-200')
                    .text(data.message);

                // Update photo preview if new URL returned
                if (data.profile_photo_url) {
                    $('#photo-preview').html(
                        '<img src="' + data.profile_photo_url + '" alt="Profile" class="h-20 w-20 rounded-full object-cover border-2 border-indigo-100">'
                    );
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function (field) {
                        $('#error-' + field).removeClass('hidden').text(errors[field][0]);
                    });
                } else {
                    $feedback
                        .removeClass('hidden bg-green-50 text-green-700 border-green-200')
                        .addClass('bg-red-50 text-red-700 border border-red-200')
                        .text('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false).text('Save Changes');
            }
        });
    });
});
</script>
@endpush

