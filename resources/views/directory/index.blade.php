@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
    <x-page-header title="Alumni Directory" subtitle="Find and connect with verified BUBT CSE alumni." />

    {{-- Search & Filters --}}
    <x-card class="mb-8">
        <form method="GET" action="{{ route('directory.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or intake…"
                            class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                </div>
                <div>
                    <select name="intake" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Intakes</option>
                        @foreach ($intakes as $intake)
                            <option value="{{ $intake }}" @selected(request('intake') == $intake)>Intake {{ $intake }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <select name="shift" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">All Shifts</option>
                        <option value="day" @selected(request('shift') === 'day')>Day</option>
                        <option value="evening" @selected(request('shift') === 'evening')>Evening</option>
                    </select>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Search
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    {{-- View Toggle --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">
            {{ $alumni->total() }} {{ Str::plural('alumni', $alumni->total()) }} found
            @if ($mapAlumni->count() > 0)
                &middot; {{ $mapAlumni->count() }} on map
            @endif
        </p>
        <div class="inline-flex rounded-xl border border-gray-200 bg-white p-1">
            <button type="button" id="view-grid" class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-colors bg-indigo-600 text-white">
                <svg class="w-4 h-4 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Grid
            </button>
            <button type="button" id="view-map" class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-colors text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Map
            </button>
        </div>
    </div>

    {{-- Grid View --}}
    <div id="grid-view">
        @if ($alumni->isEmpty())
            <x-card>
                <x-empty-state icon="users" title="No alumni found" description="No alumni found matching your criteria. Try adjusting your filters." />
            </x-card>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($alumni as $alumnus)
                    <a href="{{ route('profile.show', $alumnus) }}" class="group">
                        <x-card :hover="true" class="h-full">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0">
                                    @if ($alumnus->profile_photo)
                                        <img src="{{ Storage::disk('public')->url($alumnus->profile_photo) }}"
                                             alt="{{ $alumnus->name }}"
                                             class="h-14 w-14 rounded-full object-cover ring-2 ring-indigo-100">
                                    @else
                                        <x-avatar :name="$alumnus->name" size="lg" />
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $alumnus->name }}</h3>
                                    @if ($alumnus->designation || $alumnus->company_name)
                                        <p class="text-xs text-gray-600 truncate">
                                            {{ $alumnus->designation }}{{ $alumnus->designation && $alumnus->company_name ? ' at ' : '' }}{{ $alumnus->company_name }}
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-500 truncate">{{ $alumnus->email }}</p>
                                    @endif
                                    <div class="mt-1.5 flex items-center gap-1.5 flex-wrap">
                                        <x-badge color="primary" size="xs">Intake {{ $alumnus->intake }}</x-badge>
                                        <x-badge color="neutral" size="xs">{{ ucfirst($alumnus->shift) }}</x-badge>
                                        @if ($alumnus->current_city)
                                            <x-badge color="info" size="xs">
                                                <svg class="w-3 h-3 inline -mt-px mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                {{ $alumnus->current_city }}
                                            </x-badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($alumnus->bio)
                                <p class="mt-3 text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $alumnus->bio }}</p>
                            @endif
                        </x-card>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $alumni->links() }}
            </div>
        @endif
    </div>

    {{-- Map View --}}
    <div id="map-view" class="hidden">
        @if ($mapAlumni->isEmpty())
            <x-card>
                <x-empty-state icon="map" title="No locations yet" description="No alumni have set their location yet. Be the first — update your profile to appear on the map!" />
            </x-card>
        @else
            <x-card :padding="false" class="overflow-hidden">
                <div id="alumni-map" class="h-[500px] sm:h-[600px] w-full"></div>
            </x-card>
        @endif
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    const $gridBtn = document.getElementById('view-grid');
    const $mapBtn = document.getElementById('view-map');
    const $gridView = document.getElementById('grid-view');
    const $mapView = document.getElementById('map-view');
    let mapInitialized = false;

    function showGrid() {
        $gridView.classList.remove('hidden');
        $mapView.classList.add('hidden');
        $gridBtn.classList.add('bg-indigo-600', 'text-white');
        $gridBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
        $mapBtn.classList.remove('bg-indigo-600', 'text-white');
        $mapBtn.classList.add('text-gray-600', 'hover:text-gray-900');
    }

    function showMap() {
        $gridView.classList.add('hidden');
        $mapView.classList.remove('hidden');
        $mapBtn.classList.add('bg-indigo-600', 'text-white');
        $mapBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
        $gridBtn.classList.remove('bg-indigo-600', 'text-white');
        $gridBtn.classList.add('text-gray-600', 'hover:text-gray-900');

        if (!mapInitialized) {
            initMap();
            mapInitialized = true;
        }
    }

    $gridBtn.addEventListener('click', showGrid);
    $mapBtn.addEventListener('click', showMap);

    function initMap() {
        const alumni = @json($mapAlumni);
        if (!alumni.length) return;

        const map = L.map('alumni-map').setView([23.8103, 90.4125], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);

        const bounds = [];

        alumni.forEach(function (a) {
            const initials = a.name.charAt(0).toUpperCase();
            const photoHtml = a.photo
                ? '<img src="' + a.photo + '" class="w-10 h-10 rounded-full object-cover" alt="' + a.name + '">'
                : '<div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">' + initials + '</div>';

            const workLine = a.designation
                ? '<p class="text-xs text-gray-500">' + a.designation + (a.company ? ' at ' + a.company : '') + '</p>'
                : (a.company ? '<p class="text-xs text-gray-500">' + a.company + '</p>' : '');

            const cityLine = a.city
                ? '<p class="text-xs text-gray-400">' + a.city + '</p>'
                : '';

            const popup = '<div class="flex items-center gap-3 min-w-[200px]">' +
                photoHtml +
                '<div>' +
                    '<a href="' + a.url + '" class="text-sm font-semibold text-gray-900 hover:text-indigo-600">' + a.name + '</a>' +
                    '<p class="text-xs text-gray-500">Intake ' + a.intake + ' · ' + a.shift.charAt(0).toUpperCase() + a.shift.slice(1) + '</p>' +
                    workLine +
                    cityLine +
                '</div>' +
            '</div>';

            L.marker([a.lat, a.lng]).addTo(map).bindPopup(popup);
            bounds.push([a.lat, a.lng]);
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }

        // Fix map size when container becomes visible
        setTimeout(function () { map.invalidateSize(); }, 100);
    }
})();
</script>
@endpush
