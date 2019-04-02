<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'user_id' => function() {
            return factory('App\Models\User')->create()->id;
        }
    ];
});
