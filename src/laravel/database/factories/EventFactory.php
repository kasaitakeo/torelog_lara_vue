<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    return [
        'user_id' => $user->id,
        'event_part' => '胸',
        'event_name' => 'ベンチプレス',
        'event_explanation' => $faker->realText(30),
    ];
});
