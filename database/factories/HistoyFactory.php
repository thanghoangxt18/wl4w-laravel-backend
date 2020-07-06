<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Factory;
use Faker\Generator as Faker;

$factory->define(\App\Models\History::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->numberBetween(1,5),
        'item_id'=>$faker->numberBetween(1,10),
        'exercise_id'=>$faker->numberBetween(1,20)
    ];
});
