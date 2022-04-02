<?php

use App\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedQuestionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createQuestion("44.931568163921945", "26.024826548735035", "Unde s-a nascut Tudor?");
        $this->createQuestion("45.410855956775656", "25.534211269217028", "Unde s-a nascut Ana?");
        $this->createQuestion("44.419306271399215", "26.10575481152289", "Unde s-a nascut Andrei?");
        $this->createQuestion("44.43919127535728", "26.047917482120337", "Locatie sediu BEST Bucharest?");
    }
    /**
     * @param string $longitudeAnswer
     * @param string $latitudeAnswer
     * @param string $question
     */
    private function createQuestion(string $longitudeAnswer, string $latitudeAnswer, string $question): void
    {
        Question::create([
            'longitude_answer' => $longitudeAnswer,
            'latitude_answer' => $latitudeAnswer,
            'text' => $question,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
