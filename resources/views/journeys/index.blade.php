@extends('layouts.app')

@section('title', __('messages.journeys_title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ __('messages.journeys_title') }}</h1>
        @auth
            <a href="{{ route('journeys.create') }}" class="btn btn-primary">{{ __('messages.create_journey') }}</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login to Create Journey</a>
        @endauth
    </div>

    <div class="row">
        @foreach($journeys as $journey)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    @if($journey->cover_image_path)
                        <img src="{{ asset('storage/' . $journey->cover_image_path) }}" class="card-img-top" alt="{{ $journey->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-white-50">No Image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $journey->title }}</h5>
                        <p class="card-text text-muted small">
                            {{ $journey->main_city }}, {{ $journey->main_country }}
                            @if($journey->days) · {{ $journey->days }} days @endif
                        </p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($journey->summary, 100) }}</p>
                        <a href="{{ route('journeys.show', $journey) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $journeys->links() }}
@endsection