<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email'=>$faker->unique()->email,
        'password'=>$faker->word,
        'token'=>$faker->sha256(20),
        'name'=>$faker->name,
        'phone'=>$faker->phoneNumber,
        'avatar'=>$faker->url,
        'role_id'=>$faker->numberBetween(1,2)
    ];
});
