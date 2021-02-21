<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // デフォルトでTimestampが設定されているのでfalseにします。
    public $timestamps = false;

    // いいねしているかどうかの判定処理
    // これはいいねを押した際にツイートに対して既にいいね済みであればfalse、逆に存在しなければtrue これで正しいデータが飛んできたかどうかを判定
    public function isFavorite($user_id, $log_id) 
    {
        return (boolean) $this->where('user_id', $user_id)->where('log_id', $log_id)->first();
    }

    public function storeFavorite(Int $user_id, Int $log_id)
    {
        $this->user_id = $user_id;

        $this->log_id = $log_id;
        
        $this->save();

        return;
    }

    public function destroyFavorite($favorite_id)
    {
        return $this->where('id', $favorite_id)->delete();
    }
}
