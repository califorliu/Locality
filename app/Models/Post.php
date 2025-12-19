<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'body', 'category',
        'latitude', 'longitude', 'city', 'country', 'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function votes()
    {
        return $this->hasMany(\App\Models\Vote::class);
    }

    public function photos()
    {
        return $this->hasMany(\App\Models\PostPhoto::class)->orderBy('order');
    }

    public function scopeTrending($query)
    {
        return $query->orderByDesc('likes_count')->orderByDesc('created_at');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }
}