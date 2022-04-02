<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "questions";

    protected $fillable = [
        'longitude_answer',
        'latitude_answer',
        'text',
    ];

    public function gamesQuestion()
    {
        return $this->belongsTo(GameQuestion::class);
    }
}
