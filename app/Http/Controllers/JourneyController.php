<?php

namespace App\Http\Controllers;

use App\Models\Journey;
use App\Models\JourneyNode;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JourneyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $journeys = Journey::with('user')
            ->where('visibility', 'public')
            ->latest()
            ->simplePaginate(12);

        return view('journeys.index', compact('journeys'));
    }

    public function show(Journey $journey)
    {
        $journey->load(['user', 'nodes.post']);
        return view('journeys.show', compact('journey'));
    }

    public function create()
    {
        $userPosts = Post::where('user_id', Auth::id())->get();
        return view('journeys.create', compact('userPosts'));
    }

    public function getPostData(Post $post)
    {
        return response()->json([
            'title' => $post->title,
            'city' => $post->city,
            'country' => $post->country,
            'latitude' => $post->latitude,
            'longitude' => $post->longitude,
            'category' => $post->category,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'main_city' => 'nullable|string|max:255',
            'main_country' => 'nullable|string|max:255',
            'days' => 'nullable|integer|min:1',
            'visibility' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nodes' => 'required|array|min:1',
            'nodes.*.name' => 'required|string|max:255',
            'nodes.*.type' => 'nullable|string|max:255',
            'nodes.*.latitude' => 'nullable|numeric|between:-90,90',
            'nodes.*.longitude' => 'nullable|numeric|between:-180,180',
            'nodes.*.city' => 'nullable|string|max:255',
            'nodes.*.country' => 'nullable|string|max:255',
            'nodes.*.post_id' => 'nullable|exists:posts,id',
            'nodes.*.transport_mode' => 'nullable|string|max:255',
            'nodes.*.transport_time' => 'nullable|string|max:255',
            'nodes.*.accommodation_info' => 'nullable|string',
            'nodes.*.remarks' => 'nullable|string',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('journeys', 'public');
        }

        $journey = Journey::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'main_city' => $validated['main_city'],
            'main_country' => $validated['main_country'],
            'days' => $validated['days'],
            'visibility' => $validated['visibility'],
            'cover_image_path' => $coverImagePath,
        ]);

        foreach ($validated['nodes'] as $index => $nodeData) {
            JourneyNode::create([
                'journey_id' => $journey->id,
                'order_index' => $index + 1,
                'name' => $nodeData['name'],
                'type' => $nodeData['type'] ?? null,
                'latitude' => $nodeData['latitude'] ?? null,
                'longitude' => $nodeData['longitude'] ?? null,
                'city' => $nodeData['city'] ?? null,
                'country' => $nodeData['country'] ?? null,
                'post_id' => $nodeData['post_id'] ?? null,
                'transport_mode' => $nodeData['transport_mode'] ?? null,
                'transport_time' => $nodeData['transport_time'] ?? null,
                'accommodation_info' => $nodeData['accommodation_info'] ?? null,
                'remarks' => $nodeData['remarks'] ?? null,
            ]);
        }

        return redirect()->route('journeys.show', $journey)->with('success', 'Journey created successfully!');
    }

    public function edit(Journey $journey)
    {
        if ($journey->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $journey->load(['nodes.post']);
        $userPosts = Post::where('user_id', Auth::id())->get();
        return view('journeys.edit', compact('journey', 'userPosts'));
    }

    public function update(Request $request, Journey $journey)
    {
        if ($journey->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'main_city' => 'nullable|string|max:255',
            'main_country' => 'nullable|string|max:255',
            'days' => 'nullable|integer|min:1',
            'visibility' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nodes' => 'required|array|min:1',
            'nodes.*.name' => 'required|string|max:255',
            'nodes.*.type' => 'nullable|string|max:255',
            'nodes.*.latitude' => 'nullable|numeric|between:-90,90',
            'nodes.*.longitude' => 'nullable|numeric|between:-180,180',
            'nodes.*.city' => 'nullable|string|max:255',
            'nodes.*.country' => 'nullable|string|max:255',
            'nodes.*.post_id' => 'nullable|exists:posts,id',
            'nodes.*.transport_mode' => 'nullable|string|max:255',
            'nodes.*.transport_time' => 'nullable|string|max:255',
            'nodes.*.accommodation_info' => 'nullable|string',
            'nodes.*.remarks' => 'nullable|string',
        ]);

        $coverImagePath = $journey->cover_image_path;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('journeys', 'public');
        }

        $journey->update([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'main_city' => $validated['main_city'],
            'main_country' => $validated['main_country'],
            'days' => $validated['days'],
            'visibility' => $validated['visibility'],
            'cover_image_path' => $coverImagePath,
        ]);

        // Delete existing nodes and recreate
        $journey->nodes()->delete();
        foreach ($validated['nodes'] as $index => $nodeData) {
            JourneyNode::create([
                'journey_id' => $journey->id,
                'order_index' => $index + 1,
                'name' => $nodeData['name'],
                'type' => $nodeData['type'] ?? null,
                'latitude' => $nodeData['latitude'] ?? null,
                'longitude' => $nodeData['longitude'] ?? null,
                'city' => $nodeData['city'] ?? null,
                'country' => $nodeData['country'] ?? null,
                'post_id' => $nodeData['post_id'] ?? null,
                'transport_mode' => $nodeData['transport_mode'] ?? null,
                'transport_time' => $nodeData['transport_time'] ?? null,
                'accommodation_info' => $nodeData['accommodation_info'] ?? null,
                'remarks' => $nodeData['remarks'] ?? null,
            ]);
        }

        return redirect()->route('journeys.show', $journey)->with('success', 'Journey updated successfully!');
    }

    public function destroy(Journey $journey)
    {
        if ($journey->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $journey->delete();
        return redirect()->route('journeys.index')->with('success', 'Journey deleted successfully!');
    }
}