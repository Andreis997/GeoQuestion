<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "question" => $this->resource->text
        ];
    }
}
