<?php

class QuestionResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            "question" => $this->resource->text
        ];
    }
}
