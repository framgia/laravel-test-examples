<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(App\Street::class, function (Faker $faker) use ($factory) {
    return [
        'city_id' => $factory->create(App\City::class)->id,
        'name' => $faker->streetName,
    ];
});
