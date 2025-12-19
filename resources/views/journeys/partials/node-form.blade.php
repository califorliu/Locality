<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('messages.create_node') }} {{ $index + 1 }}</span>
        @if($index > 0)
        <button type="button" class="btn btn-sm btn-danger" onclick="removeNode({{ $index }})">{{ __('messages.create_remove') }}</button>
        @endif
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">{{ __('messages.create_node_name') }} *</label>
            <input type="text" class="form-control @error('nodes.'.$index.'.name') is-invalid @enderror" 
                   name="nodes[{{ $index }}][name]" value="{{ $node['name'] ?? '' }}" required>
            @error('nodes.'.$index.'.name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_node_type') }}</label>
                <select class="form-select" name="nodes[{{ $index }}][type]">
                    <option value="">{{ __('messages.create_node_type_select') }}</option>
                    <option value="sight" @if(($node['type'] ?? '')==='sight') selected @endif>{{ __('messages.create_node_type_sight') }}</option>
                    <option value="food" @if(($node['type'] ?? '')==='food') selected @endif>{{ __('messages.create_node_type_food') }}</option>
                    <option value="accommodation" @if(($node['type'] ?? '')==='accommodation') selected @endif>{{ __('messages.create_node_type_accommodation') }}</option>
                    <option value="transport" @if(($node['type'] ?? '')==='transport') selected @endif>{{ __('messages.create_node_type_transport') }}</option>
                    <option value="activity" @if(($node['type'] ?? '')==='activity') selected @endif>{{ __('messages.create_node_type_activity') }}</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_node_link_to_post') }}</label>
                <select class="form-select post-selector" 
                        name="nodes[{{ $index }}][post_id]"
                        data-node-index="{{ $index }}"
                        onchange="fillFromPost(this, {{ $index }})">
                    <option value="">None</option>
                    @foreach($userPosts as $post)
                    <option value="{{ $post->id }}" 
                            data-title="{{ $post->title }}"
                            data-city="{{ $post->city }}"
                            data-country="{{ $post->country }}"
                            data-lat="{{ $post->latitude }}"
                            data-lng="{{ $post->longitude }}"
                            data-category="{{ $post->category }}"
                            @if(($node['post_id'] ?? '')==$post->id) selected @endif>
                        {{ $post->title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_latitude') }}</label>
                <input type="number" step="any" class="form-control" name="nodes[{{ $index }}][latitude]" 
                       value="{{ $node['latitude'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_longitude') }}</label>
                <input type="number" step="any" class="form-control" name="nodes[{{ $index }}][longitude]" 
                       value="{{ $node['longitude'] ?? '' }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_city') }}</label>
                <input type="text" class="form-control" name="nodes[{{ $index }}][city]" 
                       value="{{ $node['city'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_country') }}</label>
                <input type="text" class="form-control" name="nodes[{{ $index }}][country]" 
                       value="{{ $node['country'] ?? '' }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_node_transport_mode') }}</label>
                <input type="text" class="form-control" name="nodes[{{ $index }}][transport_mode]" 
                       value="{{ $node['transport_mode'] ?? '' }}" placeholder="e.g., walk, bus, train">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('messages.create_node_transport_time') }}</label>
                <input type="text" class="form-control" name="nodes[{{ $index }}][transport_time]" 
                       value="{{ $node['transport_time'] ?? '' }}" placeholder="e.g., 25 min, 2h">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('messages.create_node_accommodation_info') }}</label>
            <textarea class="form-control" name="nodes[{{ $index }}][accommodation_info]" rows="2">{{ $node['accommodation_info'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('messages.create_node_remarks') }}</label>
            <textarea class="form-control" name="nodes[{{ $index }}][remarks]" rows="2">{{ $node['remarks'] ?? '' }}</textarea>
        </div>
    </div>
</div>

