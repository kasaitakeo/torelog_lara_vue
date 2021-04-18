<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EventLog;
use App\Models\Event;
use App\Models\Log;
use Faker\Generator as Faker;

$factory->define(EventLog::class, function (Faker $faker) {
    $event = factory(Event::class)->create();
    $log = factory(Log::class)->create();
    return [
        'log_id' => $log->id,
        'event_id' => $event->id,
        'weight' => 30,
        'rep' => 10,
        'set' => 5,
    ];
});
