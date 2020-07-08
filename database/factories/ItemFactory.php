<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name'=>$faker->randomElement([
            'day 1',
            'day 2',
            'day 3',
            'day 4'
        ]),
        'course_id'=>$faker->numberBetween(1,10),
    ];
});
