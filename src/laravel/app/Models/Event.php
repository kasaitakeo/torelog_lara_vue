<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\User;
use App\Models\EventLog;

class Event extends Model
{
    protected $fillable = [
        'event_name',
        'event_explanation',
        'event_part',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventLogs()
    {
        return $this->hasMany(EventLog::class);
    }
}
