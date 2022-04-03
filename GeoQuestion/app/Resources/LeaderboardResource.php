<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    public function toArray($request)
    {
        $score = 0;
        $this->resource->gamesQuestions->each(function ($item) use (&$score) {
            $score += $item->score;
        });
        return [
            "email" => $this->resource->user->name,
            "score" => $score,
        ];
    }
}
