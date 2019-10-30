<?php

use Faker\Generator as Faker;
use App\Models\Question;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 4),
        'tag_category_id' => $faker->numberBetween($min = 1, $max = 4),
        'title' => $faker->realText($maxNbChars = 50, $indexSize = 1),
        'content' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'created_at' => $faker->dateTime($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTime($max = 'now', $timezone = null),
    ];
});