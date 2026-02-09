<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,string $type, int $id)
    {
        $likeable = $this->findlikeable($type, $id);

        Gate::authorize('create', [Like::class, $likeable]);

        // $this->authorize("create", Like::class);
        $likeable->likes()->create([
            'user_id'=> $request->user()->id,
        ]);
        $likeable->increment('likes_count');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $type, int $id)
    {
        $likeable = $this->findlikeable($type, $id);

        Gate::authorize('delete', arguments: [Like::class, $likeable]);

        // $this->authorize("create", Like::class);
        $likeable->likes()->delete([
            'user_id'=> $request->user()->id,
        ]);
        $likeable->decrement('likes_count');
        return back();
    }

    protected function findlikeable($type, $id): Model 
    {
        $modelClassName = Relation::getMorphedModel($type);
        if($modelClassName === null){
            throw new ModelNotFoundException();
        }

        return $modelClassName::findOrFail($id);
    }
}
