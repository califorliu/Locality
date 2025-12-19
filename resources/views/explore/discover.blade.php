@extends('layouts.app')

@section('title', __('messages.discover'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">{{ __('messages.discover') }}</h1>
        <a href="{{ route('explore.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.back_to_explore') }}</a>
    </div>

    <ul class="nav nav-tabs mb-4" id="discoverTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="random-tab" data-bs-toggle="tab" 
                    data-bs-target="#random" type="button" role="tab">
                {{ __('messages.random') }}
            </button>
        </li>    
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="trending-tab" data-bs-toggle="tab" 
                    data-bs-target="#trending" type="button" role="tab">
                {{ __('messages.trending') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="discoverTabsContent">
    <div class="tab-pane fade show active" id="random" role="tabpanel">
            <!-- <h2 class="h5 mb-3">{{ __('messages.random') }} Posts</h2> -->
            @if($random->count() > 0)
                <div class="row">
                    @foreach($random as $post)
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
            @else
                <div class="alert alert-info">{{ __('messages.no_posts_available') }}</div>
            @endif
        </div>
        <div class="tab-pane fade" id="trending" role="tabpanel">
            <!-- <h2 class="h5 mb-3">{{ __('messages.trending') }} Posts</h2> -->
            @if($trending->count() > 0)
                <div class="row">
                    @foreach($trending as $post)
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
            @else
                <div class="alert alert-info">{{ __('messages.no_trending_posts') }}</div>
            @endif
        </div>
    </div>
@endsection

