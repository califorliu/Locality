@extends('layouts.app')

@section('title', __('messages.nearby_title'))

@section('content')
    <h1 class="h4 mb-3">{{ __('messages.nearby_title') }}</h1>

    <!-- <p class="small text-muted">
        Allow browser location to find nearby posts. Or pass <code>?lat=...&lng=...</code> in the URL.
    </p> -->

    <div id="map" class="map-container mb-3"></div>

    <div id="nearby-list" class="row">
        @foreach($posts as $post)
            <div class="col-md-4 mb-3">
                <div class="card h-100 post-card" data-post-id="{{ $post->id }}" 
                     data-lat="{{ $post->latitude }}" data-lng="{{ $post->longitude }}"
                     style="cursor: pointer; transition: all 0.3s;">
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 150px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                            <span class="text-white-50">No Image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted small">
                            {{ $post->city }}, {{ $post->country }}
                        </p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($post->body, 80) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
        const initialLat = {{ $lat ?? 'null' }};
        const initialLng = {{ $lng ?? 'null' }};

        const map = L.map('map').setView([0, 0], 2);
        const markers = {};

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let currentMarkers = {};
        let currentPosts = {};

        function loadPostsInBounds() {
            const bounds = map.getBounds();
            const minLat = bounds.getSouth();
            const minLng = bounds.getWest();
            const maxLat = bounds.getNorth();
            const maxLng = bounds.getEast();
            
            const boundsStr = `${minLat},${minLng},${maxLat},${maxLng}`;
            
            fetch(`{{ route('nearby.index') }}?bounds=${boundsStr}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Remove old markers
                Object.values(currentMarkers).forEach(marker => marker.remove());
                currentMarkers = {};
                currentPosts = {};
                
                // Clear cards
                const listContainer = document.getElementById('nearby-list');
                listContainer.innerHTML = '';
                
                // Add new markers and cards
                data.posts.forEach(function(post) {
                    currentPosts[post.id] = post;
                    
                    // Add marker
                    const marker = L.marker([post.latitude, post.longitude]).addTo(map)
                        .bindPopup(`${post.title}<br>${post.city}, ${post.country}`);
                    currentMarkers[post.id] = marker;
                    
                    // Add card
                    const cardHtml = `
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 post-card" data-post-id="${post.id}" 
                                 data-lat="${post.latitude}" data-lng="${post.longitude}"
                                 style="cursor: pointer; transition: all 0.3s;">
                                ${post.image_path ? 
                                    `<img src="/storage/${post.image_path}" class="card-img-top" alt="${post.title}" style="height: 150px; object-fit: cover;">` :
                                    `<div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <span class="text-white-50">No Image</span>
                                    </div>`
                                }
                                <div class="card-body">
                                    <h5 class="card-title">${post.title}</h5>
                                    <p class="card-text text-muted small">
                                        ${post.city}, ${post.country}
                                    </p>
                                    <p class="card-text">${post.body || ''}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    listContainer.insertAdjacentHTML('beforeend', cardHtml);
                });
                
                // Re-attach click handlers
                attachCardHandlers();
            })
            .catch(error => console.error('Error loading posts:', error));
        }

        function attachCardHandlers() {
            let selectedCard = null;
            document.querySelectorAll('.post-card').forEach(function(card) {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    const postId = this.dataset.postId;
                    const lat = parseFloat(this.dataset.lat);
                    const lng = parseFloat(this.dataset.lng);

                    // Remove highlight from previously selected card
                    if (selectedCard && selectedCard !== this) {
                        selectedCard.style.border = '';
                        selectedCard.style.boxShadow = '';
                    }

                    if (selectedCard === this) {
                        // Second click: navigate to post detail
                        const url = '{{ url("/posts") }}/' + postId;
                        window.location.href = url;
                    } else {
                        // First click: highlight card and show location on map
                        selectedCard = this;
                        this.style.border = '3px solid #007bff';
                        this.style.boxShadow = '0 0 10px rgba(0,123,255,0.5)';
                        
                        if (lat && lng && currentMarkers[postId]) {
                            map.setView([lat, lng], 15);
                            currentMarkers[postId].openPopup();
                        }
                    }
                });
            });
        }

        // Load posts when map moves (with debounce)
        let loadTimeout;
        map.on('moveend', function() {
            clearTimeout(loadTimeout);
            loadTimeout = setTimeout(loadPostsInBounds, 500);
        });

        if (initialLat && initialLng) {
            map.setView([initialLat, initialLng], 12);
            setTimeout(loadPostsInBounds, 500);
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 12);
                setTimeout(loadPostsInBounds, 500);
            });
        } else {
            // Default view - show all posts
            loadPostsInBounds();
        }
        
        // Initial card handlers for static posts
        attachCardHandlers();
    </script>
    @endpush
@endsection