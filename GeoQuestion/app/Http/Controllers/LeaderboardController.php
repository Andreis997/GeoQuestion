<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Resources\LeaderboardResource;
use App\Resources\LeaderboardResourceCollection;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function getLeaderBoard()
    {
        $gamesWithScore = DB::select("
SELECT game_id, SUM(score) sum_score
FROM `game_questions`
GROUP BY game_id
ORDER BY sum_score
 DESC LIMIT 6");
        $gamesIds = [];
        foreach ($gamesWithScore as $item) {
            $gamesIds[] = $item->game_id;
        }
        return new LeaderboardResourceCollection(Game::with(['user','gamesQuestions'])->find($gamesIds));
    }
}
