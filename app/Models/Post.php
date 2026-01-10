<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::saving(fn(self $post)=> $post->fill(['html'=>str($post->body)->markdown()]));
    }    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function title(): Attribute
    {
        return Attribute::set(
            fn ($value) => Str::title($value)
        );
    }

    public function showRoute(array $params = []){
        return route('posts.show', [$this, Str::slug($this->title), ...$params]);
    }
}
