<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Log;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
    return [
        'user_id'    => 1,
        'text' => $faker->word(),
        'created_at' => now(),
        'updated_at' => now()
    ];
});
