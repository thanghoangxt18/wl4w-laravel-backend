<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Exercise_Zone;
use Faker\Generator as Faker;

$factory->define(Exercise_Zone::class, function (Faker $faker) {
    return [
        'exercise_id'=>$faker->numberBetween(1,20),
        'zone_id'=>$faker->numberBetween(1,5)
    ];
});
