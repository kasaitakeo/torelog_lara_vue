<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\User;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Tweet.phpのgetTweet()で取得した情報に紐づいたコメント情報を取得します。
    public function getComments(Int $log_id)
    {
        return $this->with('user')->where('tweet_id', $tweet_id)->get();
    }

    // 引数にはコメントしたユーザのユーザIDとなる$user_idとコメントのデータ$dataを設定します。
    public function commentStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->log_id = $data['log_id'];
        $this->text = $data['text'];
        $this->save();

        return;
    }
    
}