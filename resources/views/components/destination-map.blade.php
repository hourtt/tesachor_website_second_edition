@props([
    'destination',
    'editable' => false,
])

@php
    $isKhmer = app()->getLocale() === 'km';
    $displayName = ($isKhmer && !empty($destination->place_name_in_khmer))
        ? $destination->place_name_in_khmer
        : $destination->place_name;

    $provinceName = ($isKhmer && !empty($destination->province?->name_in_khmer))
        ? $destination->province->name_in_khmer
        : ($destination->province?->name ?? '');

    $searchAddress = trim("{$destination->place_name}, {$destination->province?->name}, Cambodia");
    $saveUrl = $editable ? route('admins.destinations.location.update', $destination->id) : null;

    $mapConfig = [
        'lat' => $destination->latitude !== null ? (float) $destination->latitude : null,
        'lng' => $destination->longitude !== null ? (float) $destination->longitude : null,
        'displayName' => $displayName,
        'searchAddress' => $searchAddress,
        'editable' => (bool) $editable,
        'saveUrl' => $saveUrl,
    ];
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
     x-data="destinationMap(@js($mapConfig))">

    {{-- Card Header --}}
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-bold text-gray-400 capitalize tracking-wider">Location</h3>

        @if (!$editable)
            <template x-if="lat && lng">
                <a :href="'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                    <span>Open in Google Maps</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </template>
        @endif
    </div>

    {{-- Admin Search Box --}}
    @if ($editable)
        <div class="mb-3 relative" @click.outside="searchResults = []">
            <div class="relative flex items-center">
                <input x-ref="searchInput"
                       type="text"
                       x-model="searchQuery"
                       @keydown.enter.prevent="searchAddressNominatim()"
                       placeholder="Search place or address to relocate..."
                       class="w-full pl-9 pr-20 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <x-icon name="magnifying-glass" class="text-xs" />
                </div>
                <button type="button"
                        @click="searchAddressNominatim()"
                        :disabled="isSearching || !searchQuery.trim()"
                        class="absolute right-1 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-[11px] font-semibold transition disabled:opacity-40 cursor-pointer">
                    <span x-text="isSearching ? '...' : 'Search'"></span>
                </button>
            </div>

            {{-- Autocomplete Results Dropdown --}}
            <div x-show="searchResults.length > 0"
                 x-cloak
                 class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-30 max-h-48 overflow-y-auto">
                <template x-for="(res, idx) in searchResults" :key="idx">
                    <button type="button"
                            @click="selectSearchResult(res)"
                            class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-start gap-2 border-b border-gray-50 last:border-0 cursor-pointer">
                        <x-icon name="location-dot" class="text-xs text-blue-500 shrink-0 mt-0.5" />
                        <span class="line-clamp-2" x-text="res.display_name"></span>
                    </button>
                </template>
            </div>

            <p class="text-[11px] text-gray-400 mt-1">
                Drag the marker or click on the map to adjust coordinates.
            </p>
        </div>
    @endif

    {{-- Map Canvas & Status Overlays --}}
    <div class="relative h-64 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
        {{-- Map container --}}
        <div x-ref="mapContainer" class="w-full h-full" x-show="!hasError" style="z-index: 1;"></div>

        {{-- Loading / Geocoding Overlay --}}
        <div x-show="isLoading" class="absolute inset-0 bg-gray-50/90 flex flex-col items-center justify-center gap-2 text-gray-400" style="z-index: 10;">
            <x-icon name="circle-notch" class="animate-spin text-xl text-blue-500" />
            <span class="text-xs" x-text="isGeocoding ? 'Locating destination...' : 'Loading map...'"></span>
        </div>

        {{-- Fallback / Error Placeholder --}}
        <div x-show="hasError && !isLoading" class="absolute inset-0 bg-gray-50 flex flex-col items-center justify-center p-4 text-center" style="z-index: 10;">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                <x-icon name="location-dot" class="text-lg" />
            </div>
            <p class="text-xs font-semibold text-gray-700" x-text="errorMessage || 'Location not available'"></p>
            <p class="text-[11px] text-gray-400 mt-1 max-w-xs">
                @if ($editable)
                    Search an address above or click on the map to set coordinates.
                @else
                    Location coordinates are currently unavailable for this destination.
                @endif
            </p>
        </div>
    </div>

    {{-- Admin Footer Controls --}}
    @if ($editable)
        <div class="mt-4 pt-3 border-t border-gray-100 flex flex-col gap-2.5">
            {{-- Coordinates readout --}}
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-400 font-medium">Coordinates:</span>
                <span class="font-mono text-gray-600 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100 text-[11px]">
                    <span x-text="lat !== null ? Number(lat).toFixed(5) : '—'"></span>,
                    <span x-text="lng !== null ? Number(lng).toFixed(5) : '—'"></span>
                </span>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center justify-between gap-2">
                <button type="button"
                        @click="resetLocation()"
                        :disabled="!isDirty || isSaving"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                    Reset
                </button>

                <button type="button"
                        @click="saveLocation()"
                        :disabled="!isDirty || isSaving"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed shadow-xs transition cursor-pointer">
                    <template x-if="isSaving">
                        <x-icon name="circle-notch" class="animate-spin text-xs" />
                    </template>
                    <template x-if="!isSaving">
                        <x-icon name="check" class="text-xs" />
                    </template>
                    <span x-text="isSaving ? 'Saving...' : 'Save location'"></span>
                </button>
            </div>

            {{-- Status feedback alert --}}
            <template x-if="statusMessage">
                <div :class="statusType === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                     class="text-xs px-3 py-2 rounded-xl border flex items-center justify-between transition">
                    <span x-text="statusMessage"></span>
                    <button type="button" @click="statusMessage = ''" class="text-current opacity-70 hover:opacity-100 cursor-pointer">
                        <x-icon name="xmark" class="text-xs" />
                    </button>
                </div>
            </template>
        </div>
    @else
        {{-- Public Footer Readout --}}
        <template x-if="lat && lng">
            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1.5 truncate mr-2">
                    <x-icon name="location-dot" class="text-blue-500 text-xs shrink-0" />
                    <span class="truncate">{{ $displayName }}</span>
                </span>
                <span class="font-mono text-gray-400 text-[11px] shrink-0">
                    <span x-text="Number(lat).toFixed(4)"></span>, <span x-text="Number(lng).toFixed(4)"></span>
                </span>
            </div>
        </template>
    @endif
</div>

<style>
    .leaflet-control-attribution {
        display: none !important;
    }
</style>

<script>
    // Singleton Leaflet library loader
    window.loadLeaflet = window.loadLeaflet || function () {
        if (window.L && window.L.map) {
            return Promise.resolve(window.L);
        }
        if (window._leafletPromise) {
            return window._leafletPromise;
        }

        window._leafletPromise = new Promise((resolve, reject) => {
            if (!document.getElementById('leaflet-css')) {
                const link = document.createElement('link');
                link.id = 'leaflet-css';
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);
            }

            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.async = true;
            script.onload = () => {
                if (window.L && window.L.Icon && window.L.Icon.Default) {
                    delete window.L.Icon.Default.prototype._getIconUrl;
                    window.L.Icon.Default.mergeOptions({
                        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    });
                }
                resolve(window.L);
            };
            script.onerror = () => {
                window._leafletPromise = null;
                reject(new Error('Failed to load map library. Please check your internet connection.'));
            };
            document.head.appendChild(script);
        });

        return window._leafletPromise;
    };

    window.destinationMap = window.destinationMap || function (config) {
        return {
            lat: config.lat,
            lng: config.lng,
            savedLat: config.lat,
            savedLng: config.lng,
            displayName: config.displayName,
            searchAddress: config.searchAddress,
            editable: config.editable,
            saveUrl: config.saveUrl,

            searchQuery: '',
            searchResults: [],
            isSearching: false,
            isLoading: true,
            isGeocoding: false,
            isSaving: false,
            isDirty: false,
            hasError: false,
            errorMessage: '',
            statusMessage: '',
            statusType: '',

            map: null,
            marker: null,

            async init() {
                try {
                    const L = await window.loadLeaflet();
                    await this.initMap(L);
                } catch (err) {
                    this.isLoading = false;
                    this.hasError = true;
                    this.errorMessage = err.message || 'Location not available';
                }
            },

            async initMap(L) {
                if (this.lat === null || this.lng === null) {
                    await this.autoLocate();
                }

                if (this.lat === null || this.lng === null) {
                    this.isLoading = false;
                    this.hasError = true;
                    this.errorMessage = 'Location not available';
                    return;
                }

                // If map container is already initialized, clean up previous instance
                if (this.$refs.mapContainer._leaflet_id) {
                    this.$refs.mapContainer._leaflet_id = null;
                }

                const center = [Number(this.lat), Number(this.lng)];

                this.map = L.map(this.$refs.mapContainer, {
                    center,
                    zoom: 15,
                    zoomControl: true,
                    attributionControl: false,
                });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                }).addTo(this.map);

                this.marker = L.marker(center, {
                    draggable: this.editable,
                    title: this.displayName,
                }).addTo(this.map);

                this.marker.bindPopup(`<div style="font-family: inherit; font-size: 13px; font-weight: 600; color: #1e293b;">${this.displayName}</div>`);

                if (this.editable) {
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateCoords(pos.lat, pos.lng);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.updateCoords(e.latlng.lat, e.latlng.lng);
                    });
                }

                this.isLoading = false;

                // Adjust tile dimensions after DOM layout
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 200);
            },

            async autoLocate() {
                this.isGeocoding = true;
                try {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchAddress)}&countrycodes=kh&limit=1`;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    let target = data && data[0];

                    if (!target) {
                        const fbUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.displayName + ', Cambodia')}&countrycodes=kh&limit=1`;
                        const fbRes = await fetch(fbUrl, { headers: { 'Accept': 'application/json' } });
                        const fbData = await fbRes.json();
                        target = fbData && fbData[0];
                    }

                    if (target) {
                        this.lat = Number(parseFloat(target.lat).toFixed(7));
                        this.lng = Number(parseFloat(target.lon).toFixed(7));
                        this.savedLat = this.lat;
                        this.savedLng = this.lng;

                        if (this.editable && this.saveUrl) {
                            this.saveLocation(true);
                        }
                    } else {
                        this.hasError = true;
                        this.errorMessage = 'Location not available';
                    }
                } catch (err) {
                    this.hasError = true;
                    this.errorMessage = 'Location not available';
                } finally {
                    this.isGeocoding = false;
                }
            },

            async searchAddressNominatim() {
                if (!this.searchQuery.trim() || this.isSearching) return;
                this.isSearching = true;

                try {
                    const query = this.searchQuery.trim();
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=kh&limit=5`;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const results = await res.json();

                    if (results && results.length > 0) {
                        this.searchResults = results;
                        // Center on top result immediately
                        this.selectSearchResult(results[0], false);
                    } else {
                        this.searchResults = [];
                        this.statusType = 'error';
                        this.statusMessage = 'No matching places found.';
                        setTimeout(() => { if (this.statusType === 'error') this.statusMessage = ''; }, 3000);
                    }
                } catch (err) {
                    this.statusType = 'error';
                    this.statusMessage = 'Search request failed.';
                } finally {
                    this.isSearching = false;
                }
            },

            selectSearchResult(item, closeDropdown = true) {
                const newLat = parseFloat(item.lat);
                const newLng = parseFloat(item.lon);

                if (this.map && this.marker) {
                    this.map.setView([newLat, newLng], 16);
                    this.marker.setLatLng([newLat, newLng]);
                }

                this.updateCoords(newLat, newLng);

                if (closeDropdown) {
                    this.searchResults = [];
                    this.searchQuery = item.display_name.split(',')[0];
                }
            },

            updateCoords(newLat, newLng) {
                this.lat = Number(Number(newLat).toFixed(7));
                this.lng = Number(Number(newLng).toFixed(7));
                this.checkDirty();
            },

            checkDirty() {
                if (this.savedLat === null || this.savedLng === null) {
                    this.isDirty = true;
                } else {
                    const latDiff = Math.abs(this.lat - this.savedLat);
                    const lngDiff = Math.abs(this.lng - this.savedLng);
                    this.isDirty = latDiff > 0.000001 || lngDiff > 0.000001;
                }
            },

            resetLocation() {
                if (this.savedLat !== null && this.savedLng !== null) {
                    this.lat = this.savedLat;
                    this.lng = this.savedLng;
                    if (this.map && this.marker) {
                        this.marker.setLatLng([this.lat, this.lng]);
                        this.map.setView([this.lat, this.lng], 15);
                    }
                    this.isDirty = false;
                    this.statusMessage = '';
                    this.searchResults = [];
                    this.searchQuery = '';
                }
            },

            async saveLocation(silent = false) {
                if (this.lat === null || this.lng === null || !this.saveUrl) return;
                this.isSaving = true;
                if (!silent) this.statusMessage = '';

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const res = await fetch(this.saveUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({
                            latitude: this.lat,
                            longitude: this.lng,
                        }),
                    });

                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        this.savedLat = this.lat;
                        this.savedLng = this.lng;
                        this.isDirty = false;
                        if (!silent) {
                            this.statusType = 'success';
                            this.statusMessage = data.message || 'Location saved successfully!';
                            setTimeout(() => {
                                if (this.statusType === 'success') this.statusMessage = '';
                            }, 3500);
                        }
                    } else {
                        if (!silent) {
                            this.statusType = 'error';
                            this.statusMessage = data.message || 'Failed to save location.';
                        }
                    }
                } catch (err) {
                    if (!silent) {
                        this.statusType = 'error';
                        this.statusMessage = 'Network error while saving location.';
                    }
                } finally {
                    this.isSaving = false;
                }
            },
        };
    };
</script>
