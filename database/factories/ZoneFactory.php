<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Zone;
use Faker\Generator as Faker;

$factory->define(Zone::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(
            [
                'Legs',
                'Arm',
                'Head',
                'Waist',
                'Femoral'
            ]),
        'banner' => $faker->unique()->url
    ];
});
