<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $fillable = [
        
    ];

    //
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function log()
    {
        return $this->belongsTo('App\Models\Log');
    }
}
