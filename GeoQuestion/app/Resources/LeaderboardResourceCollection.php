<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeaderboardResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        $map = $this->collection->map(function ($item) use ($request) {
            return (new LeaderboardResource($item))->toArray($request);
        });
        return $map->sortByDesc("score");
    }
}
