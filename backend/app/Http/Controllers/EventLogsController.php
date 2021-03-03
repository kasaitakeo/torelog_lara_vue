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
        $event_log_data = $event_log->where('log_id', $log_id)->with('event')->get();

        return $event_log_data;
    }

    public function store(EventLog $event_log, Request $request)
    {
        $event_log->log_id = $request->input('log_id');
        $event_log->event_id = $request->input('event_id');
        $event_log->weight = $request->input('weight');
        $event_log->rep = $request->input('rep');
        $event_log->set = $request->input('set');

        $event_log->save();
    }

    public function delete(EventLog $event_log, $event_log_id)
    {
        $event_log->where('id', $event_log_id)->delete();
    }

    public function allDelete(EventLog $event_log, $log_id)
    {
        $event_log->where('log_id', $log_id)->delete();
    }
}
