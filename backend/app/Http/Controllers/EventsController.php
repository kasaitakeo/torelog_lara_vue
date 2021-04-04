<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventsController extends Controller
{
    /**
     * ログインユーザーの種目全て
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
     * 指定したユーザーIDの種目全て
     * 
     * 
     */
    public function userEvents($user_id)
    {
        $all_events = Event::where('user_id', $user_id)->get();

        return response($all_events, 200);
    }

    /**
     * Store a newly created resource in storage.
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
     * EventUpdate.vueにて使用する種目データ
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
