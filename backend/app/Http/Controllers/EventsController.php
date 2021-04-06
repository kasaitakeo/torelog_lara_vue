<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventsController extends Controller
{
    /**
     * ログインユーザーの種目全て返す
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        //
        $user = auth()->user();

        $all_events = $event->where('user_id', $user->id)->get();

        return response($all_events, 200);
    }

    /**
     * 指定したユーザーIDの種目全て返す
     * 
     * 
     */
    public function userEvents($user_id)
    {
        $all_events = Event::where('user_id', $user_id)->get();

        return response($all_events, 200);
    }

    /**
     * 種目を作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, Event $event)
    {
        //
        $user = auth()->user();

        $event->user_id = $user->id;
        $event->part = $request->input('eventPart');
        $event->event_name = $request->input('eventName');
        $event->event_explanation = $request->input('eventExplanation');

        $event->save();

        return response('', 201);
    }

    /**
     * EventUpdate.vueにて使用する種目データを返す
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event_data = event::with(['user'])
        ->where('id', $id)->first();

        return $event_data;
    }
    /**
     * 種目データの更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->part = $request->input('eventPart');
        $event->event_name = $request->input('eventName');
        $event->event_explanation = $request->input('eventExplanation');

        $event->save();

        return response('', 201);
    }

    /**
     * 種目の削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response('', 200);
    }
}
