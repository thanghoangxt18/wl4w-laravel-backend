<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name'=>$faker->unique()->randomElement([
            'group1',
            'group2',
            'group3',
            'group4',
            'group5'
        ]),
        'banner'=>$faker->url,
        'description'=>$faker->text
    ];
});
