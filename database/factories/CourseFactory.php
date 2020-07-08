<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->text,
        'layout_type' => $faker->randomElement(
            [
                'list_full_width',
                'list_half_width',
                'list_free_width',
                'list_free_width_separate',
                'grid_half_width',
                'grid_third_width'
            ]),
        'zone_id' => $faker->numberBetween(1, 5),
        'banner'=>$faker->unique()->url,
    ];
});
