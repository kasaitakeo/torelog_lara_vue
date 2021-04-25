<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\EventLog;

class Log extends Model
{
    use SoftDeletes;

    /**
     * 指定したカラムのホワイトリスト化
     * @var array
     */
    protected $fillable = [
        'title',
        'text'
    ];

    /**
     * responseで返却するデータの指定
     * @var array
     */
    protected $visible = [
        'id', 
        'title', 
        'text', 
        'user', 
        'favorites', 
        'comments', 
        'event_logs', 
        'created_at'
    ];

    /**
     * Eloquentリレーション
     * 1対1の場合
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
     * @param integer $user_id
     * @param array $follow_ids
     * @return array $logs_data
     */
    public function getFollowingTimelines(Int $user_id, Array $follow_ids) {
        // 自身とフォローしているユーザIDを結合する
        $follow_ids[] = $user_id;

        $logs_data = $this->getTimelines()
        ->whereIn('user_id', $follow_ids);
        

        return $logs_data;
    }

    /**
     * 全てのログ取得
     * @return array $logs_data
     */
    public function getAllTimelines() {
        $logs_data = $this->getTimelines();

        return $logs_data;
    }

    /**
     * 指定したユーザーのログのみ取得
     * @param integer $user_id
     * @return array $logs_data
     */
    public function getUserTimelines(Int $user_id) {
        $logs_data = $this->getTimelines()
        ->where('user_id', $user_id);

        return $logs_data;
    }

    /**
     * 同じクラス内のタイムライン取得処理
     * @return array $timelines
     */
    protected function getTimelines() {
        $timelines = $this->with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }]);

        return $timelines;
    }

    /**
     * 自分のログの投稿数の取得(UsersControllerで使用)
     * @param integer $user_id
     * @return integer
     */
    public function getLogCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }
}