<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Number;

class PostResource extends JsonResource
{
    private bool $withLikePermision = false;
    public function withLikePermision(): self
    {
        $this->withLikePermision = true;
        return $this;
    }

    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn() => UserResource::make($this->user)),
            'topic' => $this->whenLoaded('topic', fn() => TopicResource::make($this->topic)),
            'title' => $this->title,
            'body' => $this->body,
            'html' => $this->html,
            'likes_count' => Number::abbreviate($this->likes_count),     
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'routes' => [
                'show'=> $this->showRoute()
            ],
            'can'=>[
                'like'=> $this->when($this->withLikePermision, fn()=>$request->user()?->can('create', [Like::class, $this->resource])),
            ],
        ];
    }
}
