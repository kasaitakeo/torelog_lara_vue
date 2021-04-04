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

    // responseで返却するデータの指定
    protected $visible = [
        'id', 'text', 'user', 'favorites', 'comments', 'event_logs',
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
     * 自分のログの投稿数の取得(UsersControllerで使用)
     * @param Int $user_id
     * @return
     */
    public function getLogCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }
}