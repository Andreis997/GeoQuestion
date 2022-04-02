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
        $this->createQuestion("45.51509166426635", "25.367155070905362", "Where is the castle surnamed \"Dracula's Castle\"?");
        $this->createQuestion("31.272445044902348", "121.47384564338635", "Where is the longest metro in the world?");
        $this->createQuestion("-27.107148631629883", "-109.35012446398271", "Where can you find huge statue heads?");
        $this->createQuestion("44.43883027949441", "26.04763164076258", "Where do the authors of this platform study?");
        $this->createQuestion("44.405982308478166", "8.904901170407658", "Where was Christopher Columbus born?");
        $this->createQuestion("52.535322379576925", "13.39025537302395", "Where was the spot that divided \"the East\" and \"the West\"?");
        $this->createQuestion("41.8904098494266", "12.492209440209068", "Where is the colloseum?");
        $this->createQuestion("-22.951639359817577", "-43.21046574470755", "Where is Christ the Redeemer statue?");
        $this->createQuestion("40.689379365203706", "-74.04386643098893", "Where is the Statue of Liberty");
        $this->createQuestion("51.17939100227482", " -1.8261680297945706", "Where is the Stonehenge?");
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
