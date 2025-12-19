<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'journey_id', 'post_id', 'order_index', 'name', 'type',
        'latitude', 'longitude', 'city', 'country',
        'transport_mode', 'transport_time',
        'accommodation_info', 'remarks',
    ];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}