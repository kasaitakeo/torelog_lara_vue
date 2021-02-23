<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\User;

class Event extends Model
{
    //
    // SoftDeleteという論理削除（削除してもDBには残るがシステム上削除したとみなす機能）を使える様に設定
    // use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // $fillableはLaravelで用意されているメンバ変数
    // $fillableにカラム名を定義するとそれ以外のカラムを登録/更新でエラーを吐くつまりホワイトリスト。
    // 逆に$guardedというのはブラックリストで登録/更新できないカラムを指定します。基本的にはどちらでも可です
    protected $fillable = [
        'event_name',
        'event_name',
        'part',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAllEvents(Int $user_id)
    {
        // return $this->Where('user_id',  $user_id)->pluck('event_name');
        return $this->Where('user_id',  $user_id)->get();
    }

    public function eventStore(Int $user_id, Array $event_datas)
    {
        $this->user_id = $user_id;
        $this->part = $event_datas['part'];
        $this->event_name = $event_datas['event_name'];
        $this->save();

        return;
    }

    public function getEvents($event_id)
    {
        // return $this->Where('user_id',  $user_id)->pluck('event_name');
        // return $this->Where('id',  $event_ids)->get();
        // return $this->Where('id',  $event_ids)->pluck('event_name', 'part');
        return $this->Where('id',  $event_id)->first();
    }

    public function eventDestroy(Int $user_id, Int $event_id)
    {
        return $this->where('user_id', $user_id)->where('id', $event_id)->delete();
    }

}
