@extends('layouts.app')

@section('title', __('messages.edit_journey'))

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1 class="h4 mb-3">{{ __('messages.edit_journey') }}</h1>

            <form method="POST" action="{{ route('journeys.update', $journey) }}" id="journeyForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card mb-3">
                    <div class="card-header">{{ __('messages.create_journey_information') }}</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('messages.create_title') }} *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $journey->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">{{ __('messages.create_description') }}</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                      id="summary" name="summary" rows="3">{{ old('summary', $journey->summary) }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="main_city" class="form-label">{{ __('messages.create_city') }}</label>
                                <input type="text" class="form-control" id="main_city" name="main_city" 
                                       value="{{ old('main_city', $journey->main_city) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="main_country" class="form-label">{{ __('messages.create_country') }}</label>
                                <input type="text" class="form-control" id="main_country" name="main_country" 
                                       value="{{ old('main_country', $journey->main_country) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="days" class="form-label">{{ __('messages.create_days') }}</label>
                                <input type="number" class="form-control" id="days" name="days" 
                                       value="{{ old('days', $journey->days) }}" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label">{{ __('messages.create_visibility') }} *</label>
                            <select class="form-select" id="visibility" name="visibility" required>
                                <option value="public" @if(old('visibility', $journey->visibility)==='public') selected @endif>Public</option>
                                <option value="private" @if(old('visibility', $journey->visibility)==='private') selected @endif>Private</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">{{ __('messages.create_cover_image') }}</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" name="cover_image" accept="image/*">
                            <small class="form-text text-muted">{{ __('messages.create_cover_image_description') }}</small>
                            @if($journey->cover_image_path)
                                <div class="mt-2">
                                    <p class="small mb-1">Current cover image:</p>
                                    <img src="{{ asset('storage/' . $journey->cover_image_path) }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
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
                                @foreach($journey->nodes as $index => $node)
                                    <div id="node-{{ $index }}">
                                        @include('journeys.partials.node-form', [
                                            'index' => $index, 
                                            'node' => [
                                                'name' => $node->name,
                                                'type' => $node->type,
                                                'post_id' => $node->post_id,
                                                'latitude' => $node->latitude,
                                                'longitude' => $node->longitude,
                                                'city' => $node->city,
                                                'country' => $node->country,
                                                'transport_mode' => $node->transport_mode,
                                                'transport_time' => $node->transport_time,
                                                'accommodation_info' => $node->accommodation_info,
                                                'remarks' => $node->remarks,
                                            ], 
                                            'userPosts' => $userPosts
                                        ])
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('journeys.show', $journey) }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let nodeCount = {{ old('nodes') ? count(old('nodes')) : ($journey->nodes->count() > 0 ? $journey->nodes->count() : 1) }};

    function addNode() {
        const container = document.getElementById('nodes-container');
        const newNode = document.createElement('div');
        newNode.id = 'node-' + nodeCount;
        newNode.innerHTML = `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('messages.create_node') }} ${nodeCount + 1}</span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeNode(${nodeCount})">{{ __('messages.create_remove') }}</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.create_node_name') }} *</label>
                        <input type="text" class="form-control" name="nodes[${nodeCount}][name]" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_node_type') }}</label>
                            <select class="form-select post-selector" name="nodes[${nodeCount}][type]" onchange="fillFromPost(this, ${nodeCount})">
                                <option value="">{{ __('messages.create_node_type_select') }}</option>
                                <option value="sight">{{ __('messages.create_node_type_sight') }}</option>
                                <option value="food">{{ __('messages.create_node_type_food') }}</option>
                                <option value="accommodation">{{ __('messages.create_node_type_accommodation') }}</option>
                                <option value="transport">{{ __('messages.create_node_type_transport') }}</option>
                                <option value="activity">{{ __('messages.create_node_type_activity') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_node_link_to_post') }}</label>
                            <select class="form-select post-selector" name="nodes[${nodeCount}][post_id]" onchange="fillFromPost(this, ${nodeCount})">
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
                            <label class="form-label">{{ __('messages.create_latitude') }}</label>
                            <input type="number" step="any" class="form-control" name="nodes[${nodeCount}][latitude]">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_longitude') }}</label>
                            <input type="number" step="any" class="form-control" name="nodes[${nodeCount}][longitude]">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_city') }}</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][city]">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_country') }}</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][country]">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_node_transport_mode') }}</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][transport_mode]" placeholder="e.g., walk, bus, train">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.create_node_transport_time') }}</label>
                            <input type="text" class="form-control" name="nodes[${nodeCount}][transport_time]" placeholder="e.g., 25 min, 2h">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.create_node_accommodation_info') }}</label>
                        <textarea class="form-control" name="nodes[${nodeCount}][accommodation_info]" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.create_node_remarks') }}</label>
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
            alert('{{ __('messages.create_node_minimum') }}');
        }
    }

    function fillFromPost(selectElement, nodeIndex) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (!selectedOption || !selectedOption.value || selectElement.name.indexOf('[post_id]') === -1) {
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
</script>
@endpush

