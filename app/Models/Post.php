<?php

namespace App\Models;

use App\Models\Concerns\ConvertMarkdownToHTML;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use ConvertMarkdownToHTML;

    protected $guarded = [];   

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes(){
        return $this->morphMany(Like::class,"likeable");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function topic(){
        return $this->belongsTo(Topic::class);
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
