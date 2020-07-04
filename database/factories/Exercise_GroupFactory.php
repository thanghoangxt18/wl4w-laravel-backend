<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Exercise_Group;
use Faker\Generator as Faker;

$factory->define(Exercise_Group::class, function (Faker $faker) {
    return [
        'exercise_id'=>$faker->numberBetween(1,20),
        'group_id'=>$faker->numberBetween(1,5)
    ];
});
