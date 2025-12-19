@extends('layouts.app')

@section('title', $journey->title)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-2">
        <h1 class="h4 mb-0">{{ $journey->title }}</h1>
        @auth
            @if($journey->user_id === Auth::id())
                <div class="btn-group">
                    <a href="{{ route('journeys.edit', $journey) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('journeys.destroy', $journey) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this journey?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
    <p class="text-muted small">
        {{ $journey->main_city }}, {{ $journey->main_country }}
        @if($journey->days) · {{ $journey->days }} days @endif
        · by <a href="{{ route('people.show', $journey->user) }}" class="text-decoration-none">{{ $journey->user->name ?? 'Unknown' }}</a>
    </p>
    <p>{{ $journey->summary }}</p>

    <div id="map" class="map-container mb-3"></div>

    <h2 class="h5 mt-4">{{ __('messages.create_journey_nodes') }}</h2>
    <ol class="list-group list-group-numbered">
        @foreach($journey->nodes as $node)
            <li class="list-group-item">
                <div class="fw-bold">{{ $node->name }}</div>
                <div class="small text-muted">
                    {{ $node->city }}, {{ $node->country }} · {{ $node->type }}
                </div>
                @if($node->transport_mode)
                    <div class="small">
                        {{ __('messages.create_node_transport_mode') }}: {{ $node->transport_mode }}
                        @if($node->transport_time) ({{ $node->transport_time }}) @endif
                    </div>
                @endif
                @if($node->accommodation_info)
                    <div class="small">Stay: {{ $node->accommodation_info }}</div>
                @endif
                @if($node->remarks)
                    <div class="small">Notes: {{ $node->remarks }}</div>
                @endif
                @if($node->post)
                    <div class="small mt-1">
                        Linked post: <a href="{{ route('posts.show', $node->post) }}" class="text-decoration-none">
                            <strong>{{ $node->post->title }}</strong>
                        </a>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>

    @push('scripts')
    <script>
        const map = L.map('map').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const points = [];

        @foreach($journey->nodes as $node)
        @if($node->latitude && $node->longitude)
            const p{{ $node->id }} = [{{ $node->latitude }}, {{ $node->longitude }}];
            points.push(p{{ $node->id }});
            L.marker(p{{ $node->id }}).addTo(map)
                .bindPopup(`{{ addslashes($node->name) }}`);
        @endif
        @endforeach

        if (points.length > 0) {
            const polyline = L.polyline(points, {color: 'blue'}).addTo(map);
            map.fitBounds(polyline.getBounds(), {padding: [20, 20]});
        }
    </script>
    @endpush
@endsection