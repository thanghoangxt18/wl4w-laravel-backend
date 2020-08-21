<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Exercise;
use Faker\Generator as Faker;

$factory->define(Exercise::class, function (Faker $faker) {
    return [
        'name'=>$faker->unique()->word,
        'image'=>$faker->unique()->imageUrl(),
        'thumb_image'=>$faker->url,
        'description'=>$faker->text,
        'video'=>$faker->url,
        'type'=>$faker->numberBetween(1,5),
        'reps'=>$faker->numberBetween(10,15),
        'time_per_rep'=>$faker->numberBetween(1,4),
        'tts_guide'=>$faker->text,
        'met'=>$faker->numberBetween(0,200)
    ];
});
