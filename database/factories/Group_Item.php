<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\GroupItem::class, function (Faker $faker) {
    return [
        'item_id'=>$faker->numberBetween(1,30),
        'group_id'=>$faker->numberBetween(1,5),
        'order'=>$faker->numberBetween(1,5)
    ];
});
