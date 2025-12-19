<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class NearbyController extends Controller
{
    public function index(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $bounds = $request->get('bounds'); // Format: "minLat,minLng,maxLat,maxLng"

        $posts = collect();

        if ($bounds) {
            // Parse bounds from map
            $boundsArray = explode(',', $bounds);
            if (count($boundsArray) === 4) {
                $minLat = (float)$boundsArray[0];
                $minLng = (float)$boundsArray[1];
                $maxLat = (float)$boundsArray[2];
                $maxLng = (float)$boundsArray[3];
                
                $posts = Post::whereBetween('latitude', [$minLat, $maxLat])
                    ->whereBetween('longitude', [$minLng, $maxLng])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->recent()
                    ->take(100)
                    ->get();
            }
        } elseif ($lat && $lng) {
            // Fallback to center point with delta
            $delta = 0.2; // ~20km, rough
            $posts = Post::whereBetween('latitude', [$lat - $delta, $lat + $delta])
                ->whereBetween('longitude', [$lng - $delta, $lng + $delta])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->recent()
                ->take(50)
                ->get();
        }

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'posts' => $posts->map(function($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'city' => $post->city,
                        'country' => $post->country,
                        'latitude' => $post->latitude,
                        'longitude' => $post->longitude,
                        'body' => \Illuminate\Support\Str::limit($post->body, 80),
                        'image_path' => $post->image_path,
                    ];
                })
            ]);
        }

        return view('nearby.index', [
            'posts' => $posts,
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }
}