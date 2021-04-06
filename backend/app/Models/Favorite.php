<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // デフォルトでTimestampが設定されているのでfalse
    public $timestamps = false;
    

    // いいねしているかどうかの判定処理
    public function isFavorite($user_id, $log_id) 
    {
        return (boolean) $this->where('user_id', $user_id)->where('log_id', $log_id)->first();
    }

    public function storeFavorite($user_id, $log_id)
    {
        $this->user_id = $user_id;

        $this->log_id = $log_id;
        
        $this->save();

        return;
    }
}
