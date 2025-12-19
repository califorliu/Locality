<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'file_path', 'order'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
