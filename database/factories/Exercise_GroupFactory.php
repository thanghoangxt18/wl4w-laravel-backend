<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ExerciseGroup;
use Faker\Generator as Faker;

$factory->define(ExerciseGroup::class, function (Faker $faker) {
    return [
        'exercise_id'=>$faker->numberBetween(1,20),
        'group_id'=>$faker->numberBetween(1,5),
        'order' => $faker->numberBetween(1,5)
    ];
});
