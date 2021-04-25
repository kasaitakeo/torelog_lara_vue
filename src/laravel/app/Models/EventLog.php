<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLog extends Model
{
    /**
     * 指定したカラムのホワイトリスト化
     * @var array
     */
    protected $fillable = [
        'weight', 
        'rep', 
        'set'
    ];

    /**
     * Eloquentリレーション
     * １対１の場合
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    /**
     * Eloquentリレーション
     * １対１の場合
     */
    public function log()
    {
        return $this->belongsTo('App\Models\Log');
    }
}
