<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Log;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
    // テストユーザー作成
    $user = factory(User::class)->create();
    return [
        'user_id' => $user->id,
        'title' => $faker->word(),
        'text' => $faker->word(),
        'created_at' => now(),
        'updated_at' => now()
    ];
});
