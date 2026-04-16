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

                {{-- Work Information --}}
                <div class="space-y-5">
                    <h3 class="text-sm font-semibold text-gray-900">Work Information</h3>

                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ $user->company_name }}" placeholder="e.g. Google, Grameenphone"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-company_name"></p>
                    </div>

                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-700 mb-1.5">Designation</label>
                        <input type="text" name="designation" id="designation" value="{{ $user->designation }}" placeholder="e.g. Software Engineer, Project Manager"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-designation"></p>
                    </div>

                    <div>
                        <label for="company_website" class="block text-sm font-medium text-gray-700 mb-1.5">Company Website</label>
                        <input type="url" name="company_website" id="company_website" value="{{ $user->company_website }}" placeholder="https://company.com"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-company_website"></p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 my-6"></div>

                {{-- Location --}}
                <div class="space-y-5">
                    <h3 class="text-sm font-semibold text-gray-900">Location</h3>

                    <div class="relative">
                        <label for="city-search" class="block text-sm font-medium text-gray-700 mb-1.5">Current City</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="text" id="city-search" autocomplete="off" value="{{ $user->current_city }}" placeholder="Start typing a city name…"
                                class="w-full rounded-xl border border-gray-300 pl-10 pr-10 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <div id="city-spinner" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <svg class="animate-spin w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            </div>
                        </div>
                        <div id="city-suggestions" class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden"></div>
                        <input type="hidden" name="current_city" id="current_city" value="{{ $user->current_city }}">
                        <p class="hidden mt-1.5 text-sm text-red-600" id="error-current_city"></p>
                    </div>

                    <div id="location-display" class="{{ $user->latitude ? '' : 'hidden' }} flex items-center gap-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm">
                            <span class="font-medium text-emerald-800" id="location-label">{{ $user->current_city }}</span>
                            <span class="text-emerald-600 text-xs ml-2" id="location-coords">{{ $user->latitude ? "({$user->latitude}, {$user->longitude})" : '' }}</span>
                        </div>
                        <button type="button" id="clear-location" class="ml-auto text-emerald-500 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ $user->latitude }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ $user->longitude }}">

                    <div class="flex items-center gap-3">
                        <button type="button" id="detect-location-btn"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span id="detect-location-text">Use My Location</span>
                        </button>
                        <span class="text-xs text-gray-400">Or type a city name above to search</span>
                    </div>
                </div>

                <div class="h-px bg-gray-100 my-6"></div>

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

                // Update lat/lon display if geocoded by server
                if (data.user && data.user.latitude) {
                    setLocation(data.user.current_city || '', data.user.latitude, data.user.longitude);
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

    // --- Location: City Autocomplete + Geolocation ---

    let searchTimer = null;

    function setLocation(city, lat, lon) {
        $('#current_city').val(city);
        $('#latitude').val(lat);
        $('#longitude').val(lon);
        $('#city-search').val(city);
        $('#location-label').text(city);
        $('#location-coords').text('(' + parseFloat(lat).toFixed(4) + ', ' + parseFloat(lon).toFixed(4) + ')');
        $('#location-display').removeClass('hidden');
        $('#city-suggestions').addClass('hidden');
    }

    function clearLocation() {
        $('#current_city').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        $('#city-search').val('');
        $('#location-display').addClass('hidden');
        $('#location-label').text('');
        $('#location-coords').text('');
    }

    $('#clear-location').on('click', clearLocation);

    // City search with Nominatim
    $('#city-search').on('input', function () {
        const query = $(this).val().trim();
        clearTimeout(searchTimer);

        if (query.length < 3) {
            $('#city-suggestions').addClass('hidden').empty();
            return;
        }

        searchTimer = setTimeout(function () {
            $('#city-spinner').removeClass('hidden');

            $.ajax({
                url: 'https://nominatim.openstreetmap.org/search',
                data: { q: query, format: 'json', limit: 5, addressdetails: 1 },
                headers: { 'Accept-Language': 'en' },
                success: function (results) {
                    const $list = $('#city-suggestions').empty();

                    if (!results.length) {
                        $list.html('<div class="px-4 py-3 text-sm text-gray-400">No results found</div>');
                        $list.removeClass('hidden');
                        return;
                    }

                    results.forEach(function (place) {
                        const $item = $('<button type="button">')
                            .addClass('w-full text-left px-4 py-2.5 hover:bg-indigo-50 transition-colors flex items-start gap-3 border-b border-gray-50 last:border-0')
                            .html(
                                '<svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' +
                                '<div class="min-w-0">' +
                                    '<p class="text-sm font-medium text-gray-900 truncate">' + place.display_name.split(',').slice(0, 2).join(', ') + '</p>' +
                                    '<p class="text-xs text-gray-400 truncate">' + place.display_name + '</p>' +
                                '</div>'
                            )
                            .on('click', function () {
                                const cityName = place.display_name.split(',').slice(0, 2).join(', ').trim();
                                setLocation(cityName, place.lat, place.lon);
                            });

                        $list.append($item);
                    });

                    $list.removeClass('hidden');
                },
                error: function () {
                    $('#city-suggestions').html('<div class="px-4 py-3 text-sm text-red-500">Search failed. Try again.</div>').removeClass('hidden');
                },
                complete: function () {
                    $('#city-spinner').addClass('hidden');
                }
            });
        }, 400);
    });

    // Close suggestions on click outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#city-search, #city-suggestions').length) {
            $('#city-suggestions').addClass('hidden');
        }
    });

    // Keyboard navigation for suggestions
    $('#city-search').on('keydown', function (e) {
        const $items = $('#city-suggestions button');
        if (!$items.length) return;

        const $active = $items.filter('.bg-indigo-50');
        let idx = $items.index($active);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            $items.removeClass('bg-indigo-50');
            idx = (idx + 1) % $items.length;
            $items.eq(idx).addClass('bg-indigo-50');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            $items.removeClass('bg-indigo-50');
            idx = idx <= 0 ? $items.length - 1 : idx - 1;
            $items.eq(idx).addClass('bg-indigo-50');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (idx >= 0) $items.eq(idx).trigger('click');
        } else if (e.key === 'Escape') {
            $('#city-suggestions').addClass('hidden');
        }
    });

    // Geolocation detection (reverse geocode to get city name)
    $('#detect-location-btn').on('click', function () {
        const $btn = $(this);
        const $text = $('#detect-location-text');

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        $text.text('Detecting…');
        $btn.prop('disabled', true).addClass('opacity-60');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Reverse geocode to get city name
                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/reverse',
                    data: { lat: lat, lon: lon, format: 'json', zoom: 10 },
                    headers: { 'Accept-Language': 'en' },
                    success: function (result) {
                        const city = result.address
                            ? (result.address.city || result.address.town || result.address.village || result.address.county || 'Unknown')
                            : 'Unknown';
                        const country = result.address ? (result.address.country || '') : '';
                        const label = country ? city + ', ' + country : city;
                        setLocation(label, lat, lon);
                        $text.text('Location Detected ✓');
                    },
                    error: function () {
                        setLocation('My Location', lat, lon);
                        $text.text('Location Detected ✓');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).removeClass('opacity-60');
                    }
                });
            },
            function () {
                alert('Unable to retrieve your location. Please allow location access and try again.');
                $text.text('Use My Location');
                $btn.prop('disabled', false).removeClass('opacity-60');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });
});
</script>
@endpush

