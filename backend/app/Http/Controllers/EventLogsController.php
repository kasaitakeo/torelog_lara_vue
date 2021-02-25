<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventLog;

class EventLogsController extends Controller
{
    //
    public function index(EventLog $event_log, $log_id)
    {
        // $event_log->where('log_id', $log_id)->toArray();
        $event_log->where('log_id', $log_id)->get();
    }

    public function store(EventLog $event_log, Request $request)
    {
        $event_log->log_id = $request->inpout('logId');
        $event_log->event_id = $request->inpout('eventId');
        $event_log->weight = $request->inpout('weight');
        $event_log->rep = $request->inpout('rep');
        $event_log->set = $request->inpout('set');

        $event_log->save();
    }
}
