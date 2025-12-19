@extends('layouts.app')

@section('title', __('messages.create_post'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="h4 mb-3">{{ __('messages.create_post') }}</h1>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">{{ __('messages.create_title') }} *</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">{{ __('messages.create_description') }}</label>
                    <textarea class="form-control @error('body') is-invalid @enderror" 
                              id="body" name="body" rows="4">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">{{ __('messages.create_category') }}</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">{{ __('messages.create_select_category') }}</option>
                            <option value="food" @if(old('category')==='food') selected @endif>{{ __('messages.category_food') }}</option>
                            <option value="culture" @if(old('category')==='culture') selected @endif>{{ __('messages.category_culture') }}</option>
                            <option value="nature" @if(old('category')==='nature') selected @endif>{{ __('messages.category_nature') }}</option>
                            <option value="nightlife" @if(old('category')==='nightlife') selected @endif>{{ __('messages.category_nightlife') }}</option>
                            <option value="accommodation" @if(old('category')==='accommodation') selected @endif>{{ __('messages.category_accommodation') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.create_location') }}</label>
                    <p class="small text-muted">{{ __('messages.create_location_description') }}</p>
                    <div id="location-map" class="map-container mb-2" style="height: 300px;"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-2" id="get-current-location">
                        📍 {{ __('messages.create_use_current_location') }}
                    </button>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">{{ __('messages.create_latitude') }}</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude') }}" readonly>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">{{ __('messages.create_longitude') }}</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude') }}" readonly>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">{{ __('messages.create_city') }}</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city') }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">{{ __('messages.create_country') }}</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                               id="country" name="country" value="{{ old('country') }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">{{ __('messages.create_images') }}</label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                           id="images" name="images[]" multiple accept="image/*">
                    <small class="form-text text-muted">{{ __('messages.create_images_description') }}</small>
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">{{ __('messages.create_tags') }}</label>
                    <input type="text" class="form-control" id="tags" name="tags" 
                           value="{{ old('tags') }}" placeholder="e.g., budget, family-friendly, hidden-gem">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('explore.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.create_post') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let locationMap, locationMarker;
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        // Initialize map
        locationMap = L.map('location-map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(locationMap);

        // If old values exist, set them
        @if(old('latitude') && old('longitude'))
            const oldLat = {{ old('latitude') }};
            const oldLng = {{ old('longitude') }};
            locationMap.setView([oldLat, oldLng], 13);
            locationMarker = L.marker([oldLat, oldLng]).addTo(locationMap);
        @endif

        // Map click handler
        locationMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            
            if (locationMarker) {
                locationMarker.setLatLng([lat, lng]);
            } else {
                locationMarker = L.marker([lat, lng]).addTo(locationMap);
            }
        });

        // Get current location button
        document.getElementById('get-current-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    latInput.value = lat.toFixed(7);
                    lngInput.value = lng.toFixed(7);
                    
                    locationMap.setView([lat, lng], 15);
                    
                    if (locationMarker) {
                        locationMarker.setLatLng([lat, lng]);
                    } else {
                        locationMarker = L.marker([lat, lng]).addTo(locationMap);
                    }
                }, function(error) {
                    alert('Unable to get your location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        });
    </script>
    @endpush
@endsection

