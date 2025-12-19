@extends('layouts.app')

@section('title', __('messages.people_title'))

@section('content')
    <h1 class="h4 mb-3">{{ __('messages.people_title') }}</h1>

    <ul class="list-group">
        @foreach($users as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $user->name ?? 'User #'.$user->id }}</span>
                <a href="{{ route('people.show', $user) }}" class="btn btn-sm btn-outline-primary">View</a>
            </li>
        @endforeach
    </ul>

    {{ $users->links() }}
@endsection