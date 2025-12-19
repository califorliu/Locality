@extends('layouts.app')

@section('title', $user->name ?? 'User')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <h1 class="h4 mb-0">{{ $user->name ?? 'User #'.$user->id }}</h1>
            <p class="text-muted small mb-0">Member since {{ $user->created_at->toDateString() }}</p>
            @if($user->home_city)
                <p class="text-muted small mb-0">📍 {{ $user->home_city }}</p>
            @endif
        </div>
        @auth
            @if($user->id === Auth::id())
                <a href="{{ route('people.edit') }}" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            @endif
        @endauth
    </div>
    
    @if($user->bio)
        <div class="mb-3">
            <p>{{ $user->bio }}</p>
        </div>
    @endif

    <h2 class="h5 mt-4">Recent Posts</h2>
    <ul class="list-group mb-3">
        @foreach($user->posts as $post)
            <li class="list-group-item">
                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                    <strong>{{ $post->title }}</strong>
                </a>
                <span class="text-muted small">
                    · {{ $post->city }}, {{ $post->country }}
                    · {{ $post->created_at->diffForHumans() }}
                </span>
            </li>
        @endforeach
        @if($user->posts->isEmpty())
            <li class="list-group-item text-muted">No posts yet.</li>
        @endif
    </ul>

    <h2 class="h5 mt-4">Recent Journeys</h2>
    <ul class="list-group">
        @foreach($user->journeys as $journey)
            <li class="list-group-item">
                <a href="{{ route('journeys.show', $journey) }}">{{ $journey->title }}</a>
                <span class="text-muted small">
                    · {{ $journey->main_city }}, {{ $journey->main_country }}
                </span>
            </li>
        @endforeach
        @if($user->journeys->isEmpty())
            <li class="list-group-item text-muted">No journeys yet.</li>
        @endif
    </ul>
@endsection