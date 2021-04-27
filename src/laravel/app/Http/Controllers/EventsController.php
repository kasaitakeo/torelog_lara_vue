<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventsController extends Controller
{
    /**
     * 指定したユーザーidの種目全て取得
     * @param integer $user_id
     * @return array $all_events
     */
    public function userEvents($user_id)
    {
        $all_events = Event::where('user_id', $user_id)->get();

        return $all_events;
    }

    /**
     * 種目を作成
     * @param  App\Http\Requests\EventRequest  $request
     * @param  App\Models\Event  $event
     * @return void
     */
    public function store(EventRequest $request, Event $event)
    {
        //
        $user = auth()->user();

        $event->user_id = $user->id;
        $event->fill($request->validated())->save();

        $event->save();

        return response('', 201);
    }

    /**
     * EventUpdate.vueにて使用する種目データを取得
     * @param  App\Models\Event  $event
     * @return array $event_data
     */
    public function edit(Event $event)
    {
        $event_data = Event::with(['user'])->first();

        return $event_data ?? abort(404);;
    }
    /**
     * 種目データの更新
     * @param  App\Http\Requests\EventRequestt  $request
     * @param  App\Models\Event  $event
     * @return void
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->fill($request->validated())->save();

        return response('', 200);
    }

    /**
     * 種目の削除
     * @param  App\Models\Event  $event
     * @return void
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response('', 200);
    }
}
