<?php

use Faker\Generator as Faker;
use App\Models\Attendance;
use Carbon\Carbon;

$factory->define(Attendance::class, function (Faker $faker) {
    $dateTime = new Carbon($faker->dateTime()->format('Y-m-d H:i:s'));
    $hourAm = $faker->numberBetween(7, 11);
    $hourPm = $faker->numberBetween(18, 23);
    $minuteAm = $faker->numberBetween(0, 59);
    $minutePm = $faker->numberBetween(0, 59);
    $secondAm = $faker->numberBetween(0, 59);
    $secondPm = $faker->numberBetween(0, 59);
    $startTime = $dateTime->setTime($hourAm, $minuteAm, $secondAm)->toDateTimeString();
    $endTime = $dateTime->setTime($hourPm, $minutePm, $secondPm)->toDateTimeString();

    return [
        'user_id'    => $faker->numberBetween(1, 4),
        'date'       => $dateTime,
        'start_time' => $startTime,
        'end_time'   => $endTime,
    ];
});

$factory->state(Attendance::class, 'traning', function(Faker $faker) {
    return [
    'end_time' => null,
    ];
});

$factory->state(Attendance::class, 'absent', function(Faker $faker) {
    return [
    'start_time'    => null,
    'end_time'      => null,
    'absent_status' => 1,
    'absent_reason' => $faker->randomElement(['体調不良のため', '病院に行くため', '事故にあったため']),
    ];
});

$factory->state(Attendance::class, 'modify', function(Faker $faker) {
    return [
    'revision_status'  => 1,
    'revision_request' => $faker->randomElement(['出勤登録を間違えました、10時出訂正おねがします。', '電車遅延したため遅れました', '道端のお婆さん助けてました']),
    ];
});
