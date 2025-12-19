@extends('layouts.app')

@section('title', __('messages.search'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">{{ __('messages.search') }}</h1>
        <a href="{{ route('explore.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.back_to_explore') }}</a>
    </div>

    <form method="GET" action="{{ route('explore.search') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" 
                       placeholder="{{ __('messages.search_keywords') }}..." 
                       value="{{ $filters['q'] }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">{{ __('messages.category_all') }}</option>
                    <option value="food" @if($filters['category']==='food') selected @endif>{{ __('messages.category_food') }}</option>
                    <option value="culture" @if($filters['category']==='culture') selected @endif>{{ __('messages.category_culture') }}</option>
                    <option value="nature" @if($filters['category']==='nature') selected @endif>{{ __('messages.category_nature') }}</option>
                    <option value="nightlife" @if($filters['category']==='nightlife') selected @endif>{{ __('messages.category_nightlife') }}</option>
                    <option value="accommodation" @if($filters['category']==='accommodation') selected @endif>{{ __('messages.category_accommodation') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('messages.search') }}</button>
            </div>
        </div>
    </form>

    @if($posts->count() > 0)
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
                            @if($post->category)
                                <span class="badge bg-secondary mb-2">{{ ucfirst($post->category) }}</span>
                            @endif
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($post->body, 120) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $posts->withQueryString()->links() }}
    @else
        <div class="alert alert-info">
            {{ __('messages.no_posts_found') }}
        </div>
    @endif
@endsection

