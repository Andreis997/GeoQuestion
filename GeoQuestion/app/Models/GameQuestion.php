<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameQuestion extends Model
{

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
