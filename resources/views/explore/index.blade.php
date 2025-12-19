@extends('layouts.app')

@section('title', __('messages.explore_title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">{{ __('messages.explore_title') }}</h1>
        <div>
            <a href="{{ route('explore.search') }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.search') }}</a>
            <a href="{{ route('explore.discover') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.discover') }}</a>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-sm btn-success">{{ __('messages.create_post') }}</a>
            @endauth
        </div>
    </div>

    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-white-50">No Image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">{{ $post->title }}</a>
                        </h5>
                        <p class="card-text text-muted small">
                            {{ $post->city }}, {{ $post->country }} · {{ $post->created_at->diffForHumans() }}
                        </p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($post->body, 120) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $posts->withQueryString()->links() }}
@endsection