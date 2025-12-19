<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.app_name') }} - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Simple CSS via Bootstrap CDN (or replace with Tailwind if you prefer) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Leaflet CSS, only used on pages with maps --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { padding-top: 56px; }
        #map, .map-container { min-height: 300px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('explore.index') }}">{{ __('messages.app_name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('explore.index') }}">{{ __('messages.nav_explore') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('nearby.index') }}">{{ __('messages.nav_nearby') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('journeys.index') }}">{{ __('messages.nav_journeys') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('people.index') }}">{{ __('messages.nav_people') }}</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                @auth
                    <a href="{{ route('people.show', Auth::user()) }}" class="btn btn-sm btn-outline-light text-nowrap me-2">{{ __('messages.my_profile') }}</a>
                    <div class="dropdown me-2">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" 
                                id="createDropdown" data-bs-toggle="dropdown">
                                {{ __('messages.create_selector') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('posts.create') }}">{{ __('messages.create_post') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('journeys.create') }}">{{ __('messages.create_journey') }}</a></li>
                        </ul>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light text-nowrap">{{ __('messages.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light me-2">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light me-2">{{ __('messages.register') }}</a>
                @endauth

                <select class="form-select form-select-sm" id="langSelect"
                        onchange="changeLanguage(this.value)">
                    <option value="en" @if(app()->getLocale()==='en') selected @endif>English</option>
                    <option value="zh-CN" @if(app()->getLocale()==='zh-CN') selected @endif>简体中文</option>
                    <option value="zh-TW" @if(app()->getLocale()==='zh-TW') selected @endif>繁體中文</option>
                </select>
            </div>
        </div>
    </div>
</nav>

<main class="container my-3">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function changeLanguage(lang) {
        var url = window.location.pathname;
        var search = window.location.search;
        
        // Remove existing lang parameter
        var params = search.replace(/[?&]lang=[^&]*/g, '');
        
        // Add new lang parameter
        var separator = params.indexOf('?') === -1 ? '?' : '&';
        var newUrl = url + params + separator + 'lang=' + lang;
        
        window.location.href = newUrl;
    }
</script>
@stack('scripts')
</body>
</html>