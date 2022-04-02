<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    public function gamesQuestion() {
        return $this->belongsTo(GameQuestion::class);
    }
}
