<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * textのホワイトリスト化
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     * Eloquentリレーション
     * １対１の場合
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}