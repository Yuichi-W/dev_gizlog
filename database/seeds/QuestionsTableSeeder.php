<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Question;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Question::class, 1000)->create();
    }
}

