<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Follower;

class LogsController extends Controller
{
    /**
     * ログ一覧取得
     * 
     * 
     */
    public function index(Log $log, Follower $follower)
    {
        // ログインユーザー取得
        $user = auth()->user();

        // Models\Followerで定義した関数followingIdsでログインユーザーがフォローしているユーザーを取得(array)
        $follow_ids = $follower->followingIds($user->id);

        // followed_idだけ抜き出す following_idは自分のidでいらないから
        // $following_ids = $follow_ids->pluck('followed_id')->toArray();

        // Models/Logで定義したgetTimelinesでログインユーザーがフォローしているユーザーのログのみ取得
        $timelines = $log->getTimelines($user->id, $following_ids);

        return $timelines;
    }
}
