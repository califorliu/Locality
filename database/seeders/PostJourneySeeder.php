<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Journey;
use App\Models\JourneyNode;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PostJourneySeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo user if not exists
        $user = User::firstOrCreate(
            ['email' => 'demo@locality.com'],
            [
                'name' => 'Demo Traveler',
                'password' => Hash::make('password'),
            ]
        );

        // Create additional users for variety
        $users = collect([$user]);
        for ($i = 1; $i <= 5; $i++) {
            $users->push(User::firstOrCreate(
                ['email' => "traveler{$i}@locality.com"],
                [
                    'name' => "Traveler {$i}",
                    'password' => Hash::make('password'),
                ]
            ));
        }

        // Sample posts data
        $postsData = [
            ['title' => 'Amazing Street Food in Tokyo', 'body' => 'The best ramen I have ever had! Located in a small alley in Shibuya. The broth is rich and flavorful, and the noodles are perfectly cooked.', 'category' => 'food', 'city' => 'Tokyo', 'country' => 'Japan', 'latitude' => 35.6580, 'longitude' => 139.7016, 'tags' => ['budget', 'street-food', 'must-try']],
            ['title' => 'Sunset at Santorini', 'body' => 'Breathtaking sunset view from Oia. The colors are absolutely stunning. Best time to visit is around 7 PM in summer.', 'category' => 'nature', 'city' => 'Oia', 'country' => 'Greece', 'latitude' => 36.4619, 'longitude' => 25.3753, 'tags' => ['romantic', 'sunset', 'photography']],
            ['title' => 'Hidden Temple in Kyoto', 'body' => 'A peaceful temple away from the crowds. Perfect for meditation and reflection. The garden is beautifully maintained.', 'category' => 'culture', 'city' => 'Kyoto', 'country' => 'Japan', 'latitude' => 35.0116, 'longitude' => 135.7681, 'tags' => ['hidden-gem', 'peaceful', 'temple']],
            ['title' => 'Best Coffee Shop in Melbourne', 'body' => 'Third-wave coffee at its finest. The baristas are knowledgeable and the atmosphere is cozy. Great for remote work.', 'category' => 'food', 'city' => 'Melbourne', 'country' => 'Australia', 'latitude' => -37.8136, 'longitude' => 144.9631, 'tags' => ['coffee', 'work-friendly', 'local-favorite']],
            ['title' => 'Mountain Hiking in Switzerland', 'body' => 'Challenging but rewarding hike with panoramic views. The trail is well-marked. Bring plenty of water and snacks.', 'category' => 'nature', 'city' => 'Interlaken', 'country' => 'Switzerland', 'latitude' => 46.6863, 'longitude' => 7.8632, 'tags' => ['hiking', 'mountain', 'adventure']],
            ['title' => 'Traditional Market in Bangkok', 'body' => 'Vibrant market with fresh produce, street food, and local crafts. Great place to experience authentic Thai culture. Bargaining is expected!', 'category' => 'culture', 'city' => 'Bangkok', 'country' => 'Thailand', 'latitude' => 13.7563, 'longitude' => 100.5018, 'tags' => ['market', 'local', 'bargain']],
            ['title' => 'Beach Paradise in Bali', 'body' => 'Crystal clear water and white sand. Perfect for swimming and sunbathing. The beach is less crowded in the morning.', 'category' => 'nature', 'city' => 'Seminyak', 'country' => 'Indonesia', 'latitude' => -8.6833, 'longitude' => 115.1667, 'tags' => ['beach', 'relaxing', 'swimming']],
            ['title' => 'Nightlife in Berlin', 'body' => 'Incredible techno scene! The clubs have amazing sound systems and the crowd is diverse and friendly. Remember to bring cash.', 'category' => 'nightlife', 'city' => 'Berlin', 'country' => 'Germany', 'latitude' => 52.5200, 'longitude' => 13.4050, 'tags' => ['nightlife', 'techno', 'party']],
            ['title' => 'Museum of Modern Art in NYC', 'body' => 'World-class collection of contemporary art. The building itself is a masterpiece. Allow at least 3-4 hours to explore.', 'category' => 'culture', 'city' => 'New York', 'country' => 'USA', 'latitude' => 40.7614, 'longitude' => -73.9776, 'tags' => ['museum', 'art', 'culture']],
            ['title' => 'Street Art Tour in Lisbon', 'body' => 'Amazing graffiti and murals throughout the city. Each neighborhood has its own style. Best explored on foot.', 'category' => 'culture', 'city' => 'Lisbon', 'country' => 'Portugal', 'latitude' => 38.7223, 'longitude' => -9.1393, 'tags' => ['street-art', 'walking', 'photography']],
            ['title' => 'Seafood Feast in Barcelona', 'body' => 'Fresh seafood paella by the beach. The restaurant has been family-owned for generations. The sangria is also excellent.', 'category' => 'food', 'city' => 'Barcelona', 'country' => 'Spain', 'latitude' => 41.3851, 'longitude' => 2.1734, 'tags' => ['seafood', 'paella', 'beach']],
            ['title' => 'Desert Safari in Dubai', 'body' => 'Thrilling dune bashing followed by a traditional Bedouin camp experience. The sunset in the desert is magical.', 'category' => 'activity', 'city' => 'Dubai', 'country' => 'UAE', 'latitude' => 25.2048, 'longitude' => 55.2708, 'tags' => ['adventure', 'desert', 'sunset']],
            ['title' => 'Hot Springs in Iceland', 'body' => 'Natural hot springs with stunning views of the Northern Lights. The water is warm even in winter. Bring a towel!', 'category' => 'nature', 'city' => 'Reykjavik', 'country' => 'Iceland', 'latitude' => 64.1466, 'longitude' => -21.9426, 'tags' => ['hot-springs', 'northern-lights', 'winter']],
            ['title' => 'Traditional Tea Ceremony in Seoul', 'body' => 'Authentic Korean tea ceremony experience. The host explains the history and significance. Very peaceful and meditative.', 'category' => 'culture', 'city' => 'Seoul', 'country' => 'South Korea', 'latitude' => 37.5665, 'longitude' => 126.9780, 'tags' => ['tea', 'culture', 'peaceful']],
            ['title' => 'Budget Hostel in Prague', 'body' => 'Clean and friendly hostel in the city center. Great for solo travelers. The common area is perfect for meeting other travelers.', 'category' => 'accommodation', 'city' => 'Prague', 'country' => 'Czech Republic', 'latitude' => 50.0755, 'longitude' => 14.4378, 'tags' => ['budget', 'hostel', 'solo-travel']],
            ['title' => 'Cable Car Ride in Hong Kong', 'body' => 'Spectacular views of the city and harbor. The ride takes about 25 minutes. Best to go early to avoid crowds.', 'category' => 'activity', 'city' => 'Hong Kong', 'country' => 'China', 'latitude' => 22.3193, 'longitude' => 114.1694, 'tags' => ['cable-car', 'views', 'tourist']],
            ['title' => 'Wine Tasting in Tuscany', 'body' => 'Beautiful vineyard with excellent Chianti. The tour includes a traditional Italian lunch. Book in advance during peak season.', 'category' => 'food', 'city' => 'Florence', 'country' => 'Italy', 'latitude' => 43.7696, 'longitude' => 11.2558, 'tags' => ['wine', 'tuscany', 'food']],
            ['title' => 'Ancient Ruins in Rome', 'body' => 'The Colosseum is even more impressive in person. The audio guide is worth it. Go early morning to beat the heat and crowds.', 'category' => 'culture', 'city' => 'Rome', 'country' => 'Italy', 'latitude' => 41.9028, 'longitude' => 12.4964, 'tags' => ['history', 'ruins', 'must-see']],
            ['title' => 'Street Performance in Paris', 'body' => 'Talented musicians and artists perform daily. The atmosphere is lively and the crowd is appreciative. Great for people watching.', 'category' => 'culture', 'city' => 'Paris', 'country' => 'France', 'latitude' => 48.8566, 'longitude' => 2.3522, 'tags' => ['street-performance', 'music', 'entertainment']],
            ['title' => 'Mountain View Hotel in Banff', 'body' => 'Luxury hotel with stunning mountain views. The spa is excellent. Perfect for a romantic getaway or special occasion.', 'category' => 'accommodation', 'city' => 'Banff', 'country' => 'Canada', 'latitude' => 51.1784, 'longitude' => -115.5708, 'tags' => ['luxury', 'mountain', 'romantic']],
            ['title' => 'Local Market in Marrakech', 'body' => 'Colorful souk with spices, textiles, and handicrafts. The vendors are friendly but persistent. Great place to practice bargaining!', 'category' => 'culture', 'city' => 'Marrakech', 'country' => 'Morocco', 'latitude' => 31.6295, 'longitude' => -7.9811, 'tags' => ['market', 'shopping', 'culture']],
            ['title' => 'Surfing Lessons in Gold Coast', 'body' => 'Professional instructors and perfect waves for beginners. The beach is beautiful and the water is warm. Highly recommended!', 'category' => 'activity', 'city' => 'Gold Coast', 'country' => 'Australia', 'latitude' => -28.0167, 'longitude' => 153.4000, 'tags' => ['surfing', 'beach', 'lessons']],
            ['title' => 'Traditional Sushi in Osaka', 'body' => 'Omakase experience at a small, family-run restaurant. The chef is a master and explains each dish. Reservations required months in advance.', 'category' => 'food', 'city' => 'Osaka', 'country' => 'Japan', 'latitude' => 34.6937, 'longitude' => 135.5023, 'tags' => ['sushi', 'omakase', 'fine-dining']],
        ];

        // Create posts
        $createdPosts = [];
        foreach ($postsData as $index => $postData) {
            $user = $users->random();
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $postData['title'],
                'body' => $postData['body'],
                'category' => $postData['category'],
                'city' => $postData['city'],
                'country' => $postData['country'],
                'latitude' => $postData['latitude'],
                'longitude' => $postData['longitude'],
                'likes_count' => rand(0, 50),
                'bookmarks_count' => rand(0, 20),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);

            // Attach tags
            if (isset($postData['tags'])) {
                foreach ($postData['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $post->tags()->attach($tag->id);
                }
            }

            $createdPosts[] = $post;
        }

        // Add demo images to posts (using placeholder service)
        foreach ($createdPosts as $index => $post) {
            try {
                $imageUrl = "https://picsum.photos/800/600?random=" . ($index + 1000);
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent !== false) {
                    $filename = 'posts/post_' . $post->id . '.jpg';
                    Storage::disk('public')->put($filename, $imageContent);
                    $post->update(['image_path' => $filename]);
                }
            } catch (\Exception $e) {
                // Skip if image download fails
            }
        }

        // Sample journeys data
        $journeysData = [
            [
                'title' => '5 Days in Tokyo',
                'summary' => 'A perfect introduction to Japan capital city, covering food, culture, and modern attractions.',
                'main_city' => 'Tokyo',
                'main_country' => 'Japan',
                'days' => 5,
                'nodes' => [
                    ['name' => 'Shibuya Crossing', 'type' => 'sight', 'city' => 'Tokyo', 'country' => 'Japan', 'latitude' => 35.6580, 'longitude' => 139.7016, 'remarks' => 'Best viewed from the Starbucks on the second floor'],
                    ['name' => 'Senso-ji Temple', 'type' => 'sight', 'city' => 'Tokyo', 'country' => 'Japan', 'latitude' => 35.7148, 'longitude' => 139.7967, 'transport_mode' => 'train', 'transport_time' => '20 min'],
                    ['name' => 'Tsukiji Outer Market', 'type' => 'food', 'city' => 'Tokyo', 'country' => 'Japan', 'latitude' => 35.6654, 'longitude' => 139.7706, 'remarks' => 'Try the fresh sushi and tamagoyaki'],
                ],
            ],
            [
                'title' => 'European Adventure: Paris to Rome',
                'summary' => 'A romantic journey through two of Europe\'s most beautiful cities, with stops in charming towns along the way.',
                'main_city' => 'Paris',
                'main_country' => 'France',
                'days' => 10,
                'nodes' => [
                    ['name' => 'Eiffel Tower', 'type' => 'sight', 'city' => 'Paris', 'country' => 'France', 'latitude' => 48.8584, 'longitude' => 2.2945, 'remarks' => 'Book tickets in advance to skip the queue'],
                    ['name' => 'Louvre Museum', 'type' => 'sight', 'city' => 'Paris', 'country' => 'France', 'latitude' => 48.8606, 'longitude' => 2.3376, 'transport_mode' => 'walk', 'transport_time' => '15 min'],
                    ['name' => 'Hotel in Nice', 'type' => 'accommodation', 'city' => 'Nice', 'country' => 'France', 'latitude' => 43.7102, 'longitude' => 7.2620, 'accommodation_info' => 'Beachfront hotel with amazing views', 'transport_mode' => 'train', 'transport_time' => '5h 30min'],
                    ['name' => 'Colosseum', 'type' => 'sight', 'city' => 'Rome', 'country' => 'Italy', 'latitude' => 41.9028, 'longitude' => 12.4964, 'transport_mode' => 'train', 'transport_time' => '6h'],
                ],
            ],
            [
                'title' => 'Southeast Asia Backpacking',
                'summary' => 'An affordable adventure through Thailand, Vietnam, and Cambodia, perfect for budget travelers.',
                'main_city' => 'Bangkok',
                'main_country' => 'Thailand',
                'days' => 21,
                'nodes' => [
                    ['name' => 'Grand Palace', 'type' => 'sight', 'city' => 'Bangkok', 'country' => 'Thailand', 'latitude' => 13.7500, 'longitude' => 100.4926, 'remarks' => 'Dress code: cover shoulders and knees'],
                    ['name' => 'Floating Market', 'type' => 'activity', 'city' => 'Bangkok', 'country' => 'Thailand', 'latitude' => 13.7279, 'longitude' => 100.5561, 'transport_mode' => 'bus', 'transport_time' => '1h'],
                    ['name' => 'Angkor Wat', 'type' => 'sight', 'city' => 'Siem Reap', 'country' => 'Cambodia', 'latitude' => 13.4125, 'longitude' => 103.8670, 'transport_mode' => 'bus', 'transport_time' => '8h', 'remarks' => 'Sunrise tour is worth the early wake-up'],
                ],
            ],
            [
                'title' => 'Iceland Ring Road Adventure',
                'summary' => 'A complete circuit of Iceland\'s Ring Road, featuring waterfalls, geysers, and the Northern Lights.',
                'main_city' => 'Reykjavik',
                'main_country' => 'Iceland',
                'days' => 7,
                'nodes' => [
                    ['name' => 'Blue Lagoon', 'type' => 'activity', 'city' => 'Grindavik', 'country' => 'Iceland', 'latitude' => 63.8804, 'longitude' => -22.4494, 'remarks' => 'Book in advance, very popular'],
                    ['name' => 'Gullfoss Waterfall', 'type' => 'sight', 'city' => 'Golden Circle', 'country' => 'Iceland', 'latitude' => 64.3261, 'longitude' => -20.1214, 'transport_mode' => 'car', 'transport_time' => '1h 30min'],
                    ['name' => 'Jökulsárlón Glacier Lagoon', 'type' => 'sight', 'city' => 'Vatnajökull', 'country' => 'Iceland', 'latitude' => 64.0476, 'longitude' => -16.1794, 'transport_mode' => 'car', 'transport_time' => '4h'],
                ],
            ],
            [
                'title' => 'Australian East Coast',
                'summary' => 'From Sydney to Cairns, exploring beaches, rainforests, and the Great Barrier Reef.',
                'main_city' => 'Sydney',
                'main_country' => 'Australia',
                'days' => 14,
                'nodes' => [
                    ['name' => 'Sydney Opera House', 'type' => 'sight', 'city' => 'Sydney', 'country' => 'Australia', 'latitude' => -33.8568, 'longitude' => 151.2153, 'remarks' => 'Take a guided tour or see a show'],
                    ['name' => 'Bondi Beach', 'type' => 'activity', 'city' => 'Sydney', 'country' => 'Australia', 'latitude' => -33.8915, 'longitude' => 151.2767, 'transport_mode' => 'bus', 'transport_time' => '30 min'],
                    ['name' => 'Great Barrier Reef', 'type' => 'activity', 'city' => 'Cairns', 'country' => 'Australia', 'latitude' => -16.2904, 'longitude' => 145.8170, 'transport_mode' => 'flight', 'transport_time' => '3h', 'remarks' => 'Snorkeling or diving tour recommended'],
                ],
            ],
            [
                'title' => 'Mediterranean Cruise: Barcelona to Athens',
                'summary' => 'A luxury journey through the Mediterranean, combining culture, cuisine, and stunning coastlines.',
                'main_city' => 'Barcelona',
                'main_country' => 'Spain',
                'days' => 12,
                'nodes' => [
                    ['name' => 'Sagrada Familia', 'type' => 'sight', 'city' => 'Barcelona', 'country' => 'Spain', 'latitude' => 41.4036, 'longitude' => 2.1744, 'remarks' => 'Gaudis masterpiece, still under construction'],
                    ['name' => 'Amalfi Coast', 'type' => 'sight', 'city' => 'Amalfi', 'country' => 'Italy', 'latitude' => 40.6340, 'longitude' => 14.6027, 'transport_mode' => 'cruise', 'transport_time' => '2 days'],
                    ['name' => 'Acropolis', 'type' => 'sight', 'city' => 'Athens', 'country' => 'Greece', 'latitude' => 37.9715, 'longitude' => 23.7267, 'transport_mode' => 'cruise', 'transport_time' => '1 day'],
                ],
            ],
        ];

        // Create journeys
        $createdJourneys = [];
        foreach ($journeysData as $journeyData) {
            $user = $users->random();
            $journey = Journey::create([
                'user_id' => $user->id,
                'title' => $journeyData['title'],
                'summary' => $journeyData['summary'],
                'main_city' => $journeyData['main_city'],
                'main_country' => $journeyData['main_country'],
                'days' => $journeyData['days'],
                'visibility' => 'public',
                'created_at' => now()->subDays(rand(1, 60)),
            ]);
            $createdJourneys[] = $journey;

            // Create journey nodes
            foreach ($journeyData['nodes'] as $index => $nodeData) {
                JourneyNode::create([
                    'journey_id' => $journey->id,
                    'order_index' => $index + 1,
                    'name' => $nodeData['name'],
                    'type' => $nodeData['type'] ?? null,
                    'latitude' => $nodeData['latitude'] ?? null,
                    'longitude' => $nodeData['longitude'] ?? null,
                    'city' => $nodeData['city'] ?? null,
                    'country' => $nodeData['country'] ?? null,
                    'transport_mode' => $nodeData['transport_mode'] ?? null,
                    'transport_time' => $nodeData['transport_time'] ?? null,
                    'accommodation_info' => $nodeData['accommodation_info'] ?? null,
                    'remarks' => $nodeData['remarks'] ?? null,
                ]);
            }
        }

        // Add demo images to journeys
        foreach ($createdJourneys as $index => $journey) {
            try {
                $imageUrl = "https://picsum.photos/800/600?random=" . ($index + 2000);
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent !== false) {
                    $filename = 'journeys/journey_' . $journey->id . '.jpg';
                    Storage::disk('public')->put($filename, $imageContent);
                    $journey->update(['cover_image_path' => $filename]);
                }
            } catch (\Exception $e) {
                // Skip if image download fails
            }
        }

        $this->command->info('Created ' . count($createdPosts) . ' posts and ' . count($journeysData) . ' journeys!');
    }
}

