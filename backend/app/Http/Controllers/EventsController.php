<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        //
        $user = auth()->user();

        $all_events = $event->where('user_id', $user->id)->get();

        return $all_events;
    
    }
    public function userEvents($user_id)
    {
        $all_events = Event::where('user_id', $user_id)->get();

        return $all_events;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        //
        $user = auth()->user();

        $event->user_id = $user->id;
        $event->part = $request->input('eventPart');
        $event->event_name = $request->input('eventName');
        $event->event_explanation = $request->input('eventExplanation');

        $event->save();

        return response($event, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        //
        $event = EventForm::find($id);

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
        $user = auth()->user();
        $event->eventDestroy($user->id, $event->id);

        return response('', 200);
    }
}
