<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name'=>$faker->unique(true)->word,
        'course_id'=>$faker->numberBetween(1,10),
        'exercise_id'=>$faker->numberBetween(1,20)
    ];
});
