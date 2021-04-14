<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\EventLog;

class Log extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'text'
    ];

    // responseで返却するデータの指定
    protected $visible = [
        'id', 'title', 'text', 'user', 'favorites', 'comments', 'event_logs', 'created_at'
    ];

    /**
     * Eloquentリレーション
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Eloquentリレーション
     * １対多の場合(メソッド名が複数形)
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Eloquentリレーション
     * １対多の場合
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Eloquentリレーション
     * １対多の場合
     */
    public function event_logs()
    {
        return $this->hasMany(EventLog::class);
    }

    /**
     * ログインユーザーがフォローしているユーザーの全てのログ取得
     * 
     * 
     */
    public function getFollowingTimelines(Int $user_id, Array $follow_ids) {
        // 自身とフォローしているユーザIDを結合する
        $follow_ids[] = $user_id;

        $logs_data = $this->whereIn('user_id', $follow_ids)
        ->with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->orderBy('created_at', 'DESC')
        ->paginate(10);

        return $logs_data;
    }

    /**
     * 全てのログ取得
     * 
     * 
     */
    public function getAllTimelines() {
        $logs_data = $this->with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->orderBy('created_at', 'DESC')
        ->paginate(10);

        return $logs_data;
    }

    /**
     * 指定したユーザーのログのみ取得
     * 
     * 
     */
    public function getUserTimelines(Int $user_id) {
        $logs_data = $this->where('user_id', $user_id)
        ->with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->orderBy('created_at', 'DESC')
        ->paginate(10);

        return $logs_data;
    }

    /**
     * 自分のログの投稿数の取得(UsersControllerで使用)
     * @param Int $user_id
     * @return
     */
    public function getLogCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }
}