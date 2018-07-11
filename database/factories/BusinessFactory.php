<?php

use Faker\Generator as Faker;

$factory->define(App\Business::class, function (Faker $faker) {
    return [
        'businessName' => $faker->text(25),
        'businessAddress' => $faker->text(35),
        'businessType' => $faker->text(25)
    ];
});
