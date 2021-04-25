<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\EventLog;

class Event extends Model
{
    use SoftDeletes;

    /**
     * 指定したカラムのホワイトリスト化
     * @var array
     */
    protected $fillable = [
        'event_name',
        'event_explanation',
        'event_part',
    ];

    /**
     * Eloquentリレーション
     * １対１の場合
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Eloquentリレーション
     * １対多の場合(メソッド名が複数形)
     */
    public function eventLogs()
    {
        return $this->hasMany(EventLog::class);
    }
}
