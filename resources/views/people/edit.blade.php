@extends('layouts.app')

@section('title', __('messages.edit_profile'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="h4 mb-3">{{ __('messages.edit_profile') }}</h1>

            <form method="POST" action="{{ route('people.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('messages.name') }} *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('messages.email') }} *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">{{ __('messages.bio') }}</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                              id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="home_city" class="form-label">{{ __('messages.home_city') }}</label>
                    <input type="text" class="form-control @error('home_city') is-invalid @enderror" 
                           id="home_city" name="home_city" value="{{ old('home_city', $user->home_city) }}">
                    @error('home_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">{{ __('messages.avatar') }}</label>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                           id="avatar" name="avatar" accept="image/*">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($user->avatar_url)
                        <div class="mt-2">
                            <p class="small mb-1">{{ __('messages.current_avatar') }}:</p>
                            <img src="{{ asset('storage/' . $user->avatar_url) }}" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('messages.password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password">
                    <small class="form-text text-muted">{{ __('messages.password_leave_blank') }}</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('messages.password_confirm') }}</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('people.show', $user) }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

