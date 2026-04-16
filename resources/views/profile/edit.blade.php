@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-2xl mx-auto">
        <x-page-header title="Edit Profile" subtitle="Update your personal information and social links.">
            <x-button variant="ghost" size="sm" :href="route('profile.show', $user)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Public Profile
            </x-button>
        </x-page-header>

        <x-card>
            {{-- Success/Error Feedback --}}
            <div id="profile-feedback" class="hidden mb-6 rounded-xl p-4 text-sm"></div>

            <form id="profile-form" enctype="multipart/form-data">
                {{-- Profile Photo --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-5">
                        <div id="photo-preview">
                            @if ($user->profile_photo)
                                <img src="{{ Storage::disk('public')->url($user->profile_photo) }}"
                                     alt="{{ $user->name }}"
                                     class="h-20 w-20 rounded-2xl object-cover ring-2 ring-indigo-100">
                            @else
                                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="profile_photo" id="profile-photo-input" accept="image/jpeg,image/png,image/webp"
                                class="block text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer">
                            <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, or WebP. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-gray-100 my-6"></div>

                {{-- Personal Info --}}
                <div class="space-y-5">
                    <h3 class="text-sm font-semibold text-gray-900">Personal Information</h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-name"></p>
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1.5">Bio</label>
                        <textarea name="bio" id="bio" rows="4" maxlength="1000" placeholder="Tell the community about yourself…"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all resize-y">{{ $user->bio }}</textarea>
                        <p class="mt-1.5 text-xs text-gray-400"><span id="bio-count">{{ strlen($user->bio ?? '') }}</span>/1000</p>
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-bio"></p>
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ $user->whatsapp_number }}" placeholder="+8801XXXXXXXXX"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="mt-1.5 text-xs text-gray-400">Include country code for WhatsApp link to work correctly.</p>
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-whatsapp_number"></p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 my-6"></div>

                {{-- Social Links --}}
                <div class="space-y-5">
                    <h3 class="text-sm font-semibold text-gray-900">Social Links</h3>

                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1.5">Facebook Profile URL</label>
                        <input type="url" name="facebook_url" id="facebook_url" value="{{ $user->facebook_url }}" placeholder="https://facebook.com/username"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-facebook_url"></p>
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1.5">LinkedIn Profile URL</label>
                        <input type="url" name="linkedin_url" id="linkedin_url" value="{{ $user->linkedin_url }}" placeholder="https://linkedin.com/in/username"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-linkedin_url"></p>
                    </div>

                    <div>
                        <label for="website_url" class="block text-sm font-medium text-gray-700 mb-1.5">Personal Website</label>
                        <input type="url" name="website_url" id="website_url" value="{{ $user->website_url }}" placeholder="https://yourwebsite.com"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-website_url"></p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 my-6"></div>

                {{-- Tag Subscriptions --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Tag Subscriptions</h3>
                    <select name="subscribed_tags[]" id="subscribed_tags" multiple="multiple"
                        class="w-full rounded-xl border border-gray-300 text-sm">
                        @foreach ($allTags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $subscribedTagIds) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-gray-400">Follow tags to get notified when matching jobs are posted.</p>
                    <p class="hidden mt-1.5 text-sm text-red-600" id="error-subscribed_tags"></p>
                </div>

                {{-- Read-only Info --}}
                <div class="mb-6 grid grid-cols-2 gap-4 p-5 bg-gray-50/80 rounded-xl border border-gray-100">
                    <div class="col-span-2">
                        <span class="block text-xs font-medium text-gray-500 mb-0.5">Alumni ID</span>
                        <span class="text-sm text-gray-700 font-mono">{{ $user->alumni_id }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-0.5">Email</span>
                        <span class="text-sm text-gray-700">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-0.5">Mobile</span>
                        <span class="text-sm text-gray-700">{{ $user->mobile }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-0.5">Intake</span>
                        <span class="text-sm text-gray-700">{{ $user->intake }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-0.5">Shift</span>
                        <span class="text-sm text-gray-700 capitalize">{{ $user->shift }}</span>
                    </div>
                </div>

                <x-button variant="primary" class="w-full" id="profile-submit">Save Changes</x-button>
            </form>
        </x-card>
    </div>
@endsection

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

