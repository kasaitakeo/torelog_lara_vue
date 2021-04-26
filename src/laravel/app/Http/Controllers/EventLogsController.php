<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventLogRequest;
use App\Models\EventLog;

class EventLogsController extends Controller
{
    /**
     * 指定したログのidに登録されている種目ログを全て取得
     * @param App\Models\EventLog $event_log
     * @param integer $log_id
     * @return array $event_log_data
     */
    public function index(EventLog $event_log, $log_id)
    {
        $event_log_data = $event_log->where('log_id', $log_id)->with('event')->get();

        return response($event_log_data, 200);
    }

    /**
     * 行った種目のログ登録
     * @param App\Models\EventLog $event_log
     * @param App\Http\Requests\EventLogRequest $request
     * @return void
     */
    public function store(EventLog $event_log, EventLogRequest $request)
    {
        $event_log->log_id = $request->input('log_id');
        $event_log->event_id = $request->input('event_id');
        $event_log->weight = $request->input('weight');
        $event_log->rep = $request->input('rep');
        $event_log->set = $request->input('set');

        $event_log->save();

        return response('', 201);
    }

    /**
     * 指定したidの種目ログを削除
     * @param App\Models\EventLog $event_log
     * @return void
     */
    public function delete(EventLog $event_log)
    {
        $event_log->delete();

        return response('', 200);
    }

    /**
     * 指定したidのログに登録されている全ての種目ログを削除
     * @param App\Models\EventLog $event_log
     * @param integer $log_id
     * @return void
     */
    public function allDelete(EventLog $event_log, $log_id)
    {
        $event_log->where('log_id', $log_id)->delete();

        return response('', 200);
    }
}
