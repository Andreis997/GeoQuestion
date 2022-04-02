<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GameController extends Controller
{

    const GAME_COLLECTION_KEY = "gameCollection";
    private int $numOfQuestions = 10;

    public function getNextQuestion(Request $request) {
        if(!$request->session()->has(self::GAME_COLLECTION_KEY)) {
            $this->initiateGame($request);
        }
        $questionCollection = $request->session()->get(self::GAME_COLLECTION_KEY);
        $questions = $questionCollection["questions"];
        $question = $questions[$questionCollection["currentIndex"]];
        return new \QuestionResource($question);
    }

    public function postSendAnswer(Request $request) {

        $data = $request->validate([
            "longitude" => 'required|int',
            "latitude" => 'required|int',
        ]);

        $questionCollection = $request->session()->get(self::GAME_COLLECTION_KEY);
        $questions = $questionCollection["questions"];
        $question = $questions[$questionCollection["currentIndex"]];

        $lon1 = $data['longitude'];
        $lat1 = $data['latitude'];
        $distance = abs($this->distance($lat1, $lon1, $question->latitude, $question->longitude, "K"));

        $score = $this->getScore($distance);

        $this->createGameQuestion($score, $lon1, $lat1, $questionCollection['game'], $question);

        $questionCollection['currentIndex'] = $questionCollection['currentIndex'] + 1;

        $isEndGame = $this->decideEndGameAndDoAction($questionCollection, $questions, $request);

        return Response::json([
            'score' => $score,
            'isEndGame' => $isEndGame,
        ]);
    }


    /**
     */
    private function getQuestionsIds(): array
    {
        $elements = [];
        $lastQuestionId = (Question::orderBy('id', 'desc')->first())->id;
        do {

            $value = rand(1, $lastQuestionId);
            if (!in_array($value, $elements)) {
                $elements[] = $value;
            }
        } while (count($elements) < $this->numOfQuestions);

        return $elements;
    }

    /**
     * @param Request $request
     */
    private function initiateGame(Request $request): void
    {
        $game = new Game();
        $game->user()->associate($request->user());

        $game->save();

        $elements = $this->getQuestionsIds();
        $request->session()->put(self::GAME_COLLECTION_KEY, [
            'game' => $game,
            'questions' => Question::find($elements),
            'currentIndex' => 0
        ]);
    }

    private  function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * @param $distance
     * @return int
     */
    private function getScore($distance): int
    {
        if ($distance < 100) {
            $score = 100;
        } else {
            $score = 10;
        }
        return $score;
    }

    /**
     * @param int $score
     * @param $lon1
     * @param $lat1
     * @param $game
     * @param $question
     */
    private function createGameQuestion(int $score, $lon1, $lat1, $game, $question): void
    {
        $gameQuestion = GameQuestion::create([
            'score' => $score,
            'longitude_answer' => $lon1,
            'latitude_answer' => $lat1
        ]);

        $gameQuestion->game()->associate($game);
        $gameQuestion->question()->associate($question);

        $gameQuestion->save();
    }

    /**
     * @param $questionCollection
     * @param $questions
     * @param Request $request
     * @return bool
     */
    private function decideEndGameAndDoAction($questionCollection, $questions, Request $request): bool
    {
        $isEndGame = false;
        if ($questionCollection['currentIndex'] >= count($questions)) {
            $request->session()->forget(self::GAME_COLLECTION_KEY);
            $isEndGame = true;
        } else {
            $request->session()->put(self::GAME_COLLECTION_KEY, $questionCollection);
        }
        return $isEndGame;
    }

}
