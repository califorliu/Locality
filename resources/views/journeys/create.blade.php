@extends('layouts.app')

@section('title', __('messages.create_journey'))

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1 class="h4 mb-3">{{ __('messages.create_journey') }}</h1>

            <form method="POST" action="{{ route('journeys.store') }}" id="journeyForm" enctype="multipart/form-data">
                @csrf

                <div class="card mb-3">
                    <div class="card-header">{{ __('messages.create_journey_information') }}</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('messages.create_title') }} *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">{{ __('messages.create_description') }}</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                      id="summary" name="summary" rows="3">{{ old('summary') }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="main_city" class="form-label">{{ __('messages.create_city') }}</label>
                                <input type="text" class="form-control" id="main_city" name="main_city" 
                                       value="{{ old('main_city') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="main_country" class="form-label">{{ __('messages.create_country') }}</label>
                                <input type="text" class="form-control" id="main_country" name="main_country" 
                                       value="{{ old('main_country') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="days" class="form-label">{{ __('messages.create_days') }}</label>
                                <input type="number" class="form-control" id="days" name="days" 
                                       value="{{ old('days') }}" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label">{{ __('messages.create_visibility') }} *</label>
                            <select class="form-select" id="visibility" name="visibility" required>
                                <option value="public" @if(old('visibility')==='public') selected @endif>Public</option>
                                <option value="private" @if(old('visibility')==='private') selected @endif>Private</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">{{ __('messages.create_cover_image') }}</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" name="cover_image" accept="image/*">
                            <small class="form-text text-muted">{{ __('messages.create_cover_image_description') }}</small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('messages.create_journey_nodes') }}</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addNode()">+ {{ __('messages.create_add_node') }}</button>
                    </div>
                    <div class="card-body">
                        <div id="nodes-container">
                            @if(old('nodes'))
                                @foreach(old('nodes') as $index => $node)
                                    @include('journeys.partials.node-form', ['index' => $index, 'node' => $node, 'userPosts' => $userPosts])
                                @endforeach
                            @else
                                <div id="node-0">
                                    @include('journeys.partials.node-form', ['index' => 0, 'node' => null, 'userPosts' => $userPosts])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('journeys.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.create_journey') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

    @push('scripts')
<script>
    let nodeCount = {{ old('nodes') ? count(old('nodes')) : 1 }};

    function fillFromPost(selectElement, nodeIndex) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            return;
        }

        const nodeCard = selectElement.closest('.card');
        if (!nodeCard) return;

        // Fill name
        const nameInput = nodeCard.querySelector('input[name*="[name]"]');
        if (nameInput && selectedOption.dataset.title) {
            nameInput.value = selectedOption.dataset.title;
        }

        // Fill city
        const cityInput = nodeCard.querySelector('input[name*="[city]"]');
        if (cityInput && selectedOption.dataset.city) {
            cityInput.value = selectedOption.dataset.city;
        }

        // Fill country
        const countryInput = nodeCard.querySelector('input[name*="[country]"]');
        if (countryInput && selectedOption.dataset.country) {
            countryInput.value = selectedOption.dataset.country;
        }

        // Fill latitude
        const latInput = nodeCard.querySelector('input[name*="[latitude]"]');
        if (latInput && selectedOption.dataset.lat) {
            latInput.value = selectedOption.dataset.lat;
        }

        // Fill longitude
        const lngInput = nodeCard.querySelector('input[name*="[longitude]"]');
        if (lngInput && selectedOption.dataset.lng) {
            lngInput.value = selectedOption.dataset.lng;
        }

        // Fill type based on category
        const typeSelect = nodeCard.querySelector('select[name*="[type]"]');
        if (typeSelect && selectedOption.dataset.category) {
            const category = selectedOption.dataset.category.toLowerCase();
            if (['food', 'culture', 'nature', 'nightlife', 'accommodation', 'activity'].includes(category)) {
                typeSelect.value = category === 'accommodation' ? 'accommodation' : 
                                 category === 'nightlife' ? 'activity' : category;
            }
        }
    }

    function addNode() {
        const container = document.getElementById('nodes-container');
        const newNode = document.createElement('div');
        newNode.id = 'node-' + nodeCount;
        newNode.innerHTML = `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Node ${nodeCount + 1}</span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeNode(${nodeCount})">Remove</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="nodes[${nodeCount}][name]" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="nodes[${nodeCount}][type]">
                                <option value="">Select type</option>
                                <option value="sight">Sight</option>
                                <option value="food">Food</option>
                                <option value="accommodation">Accommodation</option>
                                <option value="transport">Transport</option>
                                <option value="activity">Activity</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link to Post (optional)</label>
                            <select class="form-select post-selector" 
                                    name="nodes[${nodeCount}][post_id]"
                                    onchange="fillFromPost(this, ${nodeCount})">
                                <option value="">None</option>
                                @foreach($userPosts as $post)
                                <option value="{{ $post->id }}"
                                        data-title="{{ $post->title }}"
                                        data-city="{{ $post->city }}"
                                        data-country="{{ $post->country }}"
                                        data-lat="{{ $post->latitude }}"
                                        data-lng="{{ $post->longitude }}"
                                        data-category="{{ $post->category }}">
                                    {{ $post->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" name="nodes[${nodeCount}][latitude]">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" name="nodes[${nodeCount}][longitude]">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][city]">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][country]">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transport Mode</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][transport_mode]" 
                                   placeholder="e.g., walk, bus, train">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transport Time</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][transport_time]" 
                                   placeholder="e.g., 25 min, 2h">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Accommodation Info</label>
                        <textarea class="form-control" name="nodes[${nodeCount}][accommodation_info]" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks/Tips/Fees</label>
                        <textarea class="form-control" name="nodes[${nodeCount}][remarks]" rows="2"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newNode);
        nodeCount++;
    }

    function removeNode(index) {
        const node = document.getElementById('node-' + index);
        if (node && document.querySelectorAll('#nodes-container > div').length > 1) {
            node.remove();
        } else {
            alert('You must have at least one node.');
        }
    }
</script>
@endpush

