<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Number;

class CommentResource extends JsonResource
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
            'id'=>$this->id,
            'user'=>$this->whenLoaded('user',fn () => UserResource::make($this->user)),
            'post'=>$this->WhenLoaded('post', fn () => PostResource::make($this->post)),
            'body'=>$this->body,
            'html'=>$this->html,
            'likes_count' => Number::abbreviate($this->likes_count),     
            'updated_at'=>$this->updated_at,
            'created_at'=>$this->created_at,
            'can' => [
                'delete' => $request->user()?->can('delete', $this->resource),
                'update' => $request->user()?->can('update', $this->resource),
                'like'=> $this->when($this->withLikePermision, fn()=>$request->user()?->can('create', [Like::class, $this->resource])),          
            ],
        ];
    }
}
