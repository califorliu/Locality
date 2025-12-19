<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index()
    {
        $posts = Post::recent()->with('user')->simplePaginate(12);
        return view('explore.index', compact('posts'));
    }

    public function search(Request $request)
    {
        $query = Post::query()->with('user');

        if ($q = $request->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $posts = $query->recent()->simplePaginate(12);

        return view('explore.search', [
            'posts' => $posts,
            'filters' => [
                'q' => $q ?? '',
                'category' => $category ?? '',
            ],
        ]);
    }

    public function discover()
    {
        $trending = Post::trending()->with('user')->take(10)->get();
        $random = Post::inRandomOrder()->with('user')->take(10)->get();

        return view('explore.discover', compact('trending', 'random'));
    }
}