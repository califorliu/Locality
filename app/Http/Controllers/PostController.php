<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostPhoto;
use App\Models\Tag;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('images') && count($request->file('images')) > 0) {
            $images = $request->file('images');
            $firstImage = $images[0];
            $imagePath = $firstImage->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category' => $validated['category'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'image_path' => $imagePath,
        ]);

        // Store all images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('posts', 'public');
                PostPhoto::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $post->tags()->attach($tag->id);
                }
            }
        }

        return redirect()->route('explore.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'tags', 'votes', 'photos']);
        return view('posts.show', compact('post'));
    }

    public function vote(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:known,unknown',
        ]);

        $userId = Auth::id();
        $ipAddress = $request->ip();

        // Check if user already voted (if logged in) or IP already voted
        $existingVote = Vote::where('post_id', $post->id)
            ->where(function($query) use ($userId, $ipAddress) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('ip_address', $ipAddress);
                }
            })
            ->first();

        if ($existingVote) {
            // Update existing vote
            $existingVote->update(['type' => $validated['type']]);
        } else {
            // Create new vote
            Vote::create([
                'user_id' => $userId,
                'post_id' => $post->id,
                'type' => $validated['type'],
                'ip_address' => $ipAddress,
            ]);
        }

        return redirect()->back()->with('success', 'Vote recorded!');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $post->load(['tags', 'photos']);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category' => $validated['category'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'city' => $validated['city'],
            'country' => $validated['country'],
        ]);

        // Handle new images
        if ($request->hasFile('images') && count($request->file('images')) > 0) {
            $images = $request->file('images');
            $firstImage = $images[0];
            $imagePath = $firstImage->store('posts', 'public');
            $post->update(['image_path' => $imagePath]);

            // Store all new images
            foreach ($images as $index => $image) {
                $path = $image->store('posts', 'public');
                PostPhoto::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'order' => $post->photos()->max('order') + $index + 1,
                ]);
            }
        }

        // Update tags
        $post->tags()->detach();
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $post->tags()->attach($tag->id);
                }
            }
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $post->delete();
        return redirect()->route('explore.index')->with('success', 'Post deleted successfully!');
    }
}

