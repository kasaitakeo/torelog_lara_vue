<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;

class Log extends Model
{
    use SoftDeletes;

    /**
     * $fillableはメンバ変数
     * $fillableにカラム名を定義するとそれ以外のカラムを登録/更新でエラーを吐く。(ホワイトリスト)
     * 逆に$guardedというのは登録/更新できないカラムを指定(ブラックリスト)
     * @var array
     */
    protected $fillable = [
        'text'
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

    public function event_logs()
    {
        return $this->hasMany('App\Models\EventLog');
    }

    /**
     * 自分のログのみ取得
     * @param Int $user_id
     * @return
     */
    public function getUserTimeLine(Int $user_id)
    {
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * 自分のログの投稿数の取得
     * @param Int $user_id
     * @return
     */
    public function getLogCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    /**
     * ログ一覧画面用のログ（全ユーザーの）を取得
     * @param Int $user_id
     * @param Array $follow_ids
     * @return
     */
    public function getTimeLines(Int $user_id, Array $follow_ids)
    {
        // ログインユーザー自身とフォローしているユーザーIDを結合
        $follow_ids[] = $user_id;

        // whereInメソッドは複数の該当データを絞り込める
        // カラムの値('user_id')のなかに指定した配列($follow_ids)が含まれている複数の条件を加える
        return $this->whereIn('user_id', $follow_ids)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * 詳細画面
     * ログIDを引数にログ情報を取得
     * @param INT $log_id
     * @return
     */
    public function getLog(INT $log_id)
    {
        return $this->where('id', $log_id)->first();
    }

    /**
     * ログの保存処理
     * @param Int $user_id
     * @param Array $data
     * @return 
     */
    public function logStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;

        $this->text = $data['text'];

        $this->save();

        return;
    }

    /**
     * $user_idと$tweet_idに値に一致するログを取得(ログ編集のため)
     * @param Int $user_id
     * @param Int $log_id
     * @return
     */
    public function getEditId(Int $user_id, Int $log_id)
    {
        // log_idのみを参照すれば良いのでは？
        return $this->where('user_id', $user_id)->where('id', $log_id)->first();
    }

    /**
     * ログの編集処理
     * @param Int $log_id
     * @param Array $data
     * @return 
     */
    public function logUpdate(Int $log_id, Array $data)
    {
        $this->id = $log_id;

        $this->text = $data['text'];

        $this->update();

        return;
    }

    /**
     * ログの削除処理
     * @param Int $log_id
     * @return 
     */
    public function logDestroy(Int $log_id)
    {
        return $this->where('id', $log_id)->delete();
    }

}