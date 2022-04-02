<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gamesQuestions() {
        return $this->hasMany(GameQuestion::class);
    }
}
