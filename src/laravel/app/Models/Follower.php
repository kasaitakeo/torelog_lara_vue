<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    /**
     * 指定したカラムのプライマリーキー化
     * @var array
     */
    protected $primaryKey = [
        'following_id',
        'followed_id'
    ];

    /**
     * 指定したカラムのホワイトリスト化
     * @var array
     */
    protected $fillable = [
        'following_id',
        'followed_id'
    ];

    public $timestamps = false;

    /**
     * オートインクリメントしないよう
     * @var boolean
     */
    public $incrementing = false;

    /**
     * 指定したuser_idのフォロー数取得
     * @param integer $user_id 
     * @return integer フォローカウント数
     */
    public function getFollowCount($user_id)
    {
        return $this->where('following_id', $user_id)->count();
    }

    /**
     * 指定したuser_idのフォロワー数取得
     * @param integer $user_id 
     * @return integer フォロワーカウント数
     */
    public function getFollowerCount($user_id)
    {
        return $this->where('followed_id', $user_id)->count();
    }

    /**
     * フォローしているユーザのIDを取得
     * @param integer $user_id
     * @return array ['ログインユーザーid' => 'ログインユーザーにフォローされているユーザーid']
     */
    public function followingIds(Int $user_id)
    {
        // ログインしているユーザidをフォローしているユーザーidとして、そのユーザーにフォローされているユーザidを全て取得
        return $this->where('following_id', $user_id)->get('followed_id');
    }
}