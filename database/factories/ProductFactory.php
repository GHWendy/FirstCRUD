<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
	// Given
    return [
        'name' => $faker ->name,
        'price' => $faker->randomDigitNotNull,
        'id' =>$faker->unique()->randomDigitNotNull
    ];
});
