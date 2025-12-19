@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h1 class="h4 mb-0">{{ $post->title }}</h1>
                @auth
                    @if($post->user_id === Auth::id())
                        <div class="btn-group">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
            <p class="text-muted small">
                by <a href="{{ route('people.show', $post->user) }}" class="text-decoration-none">{{ $post->user->name ?? 'Unknown' }}</a> · 
                {{ $post->city }}, {{ $post->country }} · 
                {{ $post->created_at->diffForHumans() }}
            </p>

            @if($post->category)
                <span class="badge bg-secondary mb-3">{{ ucfirst($post->category) }}</span>
            @endif

            @if($post->tags->count() > 0)
                <div class="mb-3">
                    @foreach($post->tags as $tag)
                        <span class="badge bg-info">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            @if($post->photos->count() > 0)
                <div id="postImages" class="mb-3">
                    @foreach($post->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->file_path) }}" 
                             class="img-fluid rounded mb-2" 
                             alt="{{ $post->title }}" 
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    @endforeach
                </div>
            @elseif($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid rounded mb-3" alt="{{ $post->title }}" style="max-height: 400px; width: 100%; object-fit: cover;">
            @endif

            @if($post->body)
                <div class="mb-3">
                    <p>{{ $post->body }}</p>
                </div>
            @endif

            <div class="mb-3">
                <p class="mb-2"><strong>{{ __('messages.show_did_you_know') }}</strong></p>
                <div class="btn-group" role="group">
                    <form method="POST" action="{{ route('posts.vote', $post) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="known">
                        <button type="submit" class="btn btn-success" id="vote-known-btn">
                            ✅ {{ __('messages.show_known') }} (<span id="known-count">{{ $post->votes()->where('type', 'known')->count() }}</span>)
                        </button>
                    </form>
                    <form method="POST" action="{{ route('posts.vote', $post) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="unknown">
                        <button type="submit" class="btn btn-warning" id="vote-unknown-btn">
                            ❓ {{ __('messages.show_dont_know') }} (<span id="unknown-count">{{ $post->votes()->where('type', 'unknown')->count() }}</span>)
                        </button>
                    </form>
                </div>
            </div>

            @if($post->latitude && $post->longitude)
                <div id="map" class="map-container mb-3"></div>
            @endif

            <a href="{{ route('explore.index') }}" class="btn btn-secondary">{{ __('messages.back_to_explore') }}</a>
        </div>
    </div>

    @if($post->latitude && $post->longitude)
    @push('scripts')
    <script>
        const map = L.map('map').setView([{{ $post->latitude }}, {{ $post->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([{{ $post->latitude }}, {{ $post->longitude }}]).addTo(map)
            .bindPopup(`{{ addslashes($post->title) }}`);
    </script>
    @endpush
    @endif
@endsection

