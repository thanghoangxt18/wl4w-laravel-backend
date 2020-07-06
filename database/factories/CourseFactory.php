<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name'=>$faker->unique()->word,
        'description'=>$faker->text,
        'layout_type'=>$faker->text,
        'zone_id'=>$faker->numberBetween(1,5)
    ];
});
