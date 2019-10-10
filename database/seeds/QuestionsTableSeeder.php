<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Question;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Question::class, 10000)->create();
    }
}
