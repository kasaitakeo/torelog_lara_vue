<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LogRequest;
use App\Models\User;
use App\Models\Log;
use App\Models\Follower;
use App\Models\Comment;
use App\Models\Event;

class LogsController extends Controller
{
    /**
     * ログ一覧取得
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function index(Log $log, Follower $follower)
    {
        if (auth()->user()) {
            $user = auth()->user();

            // 
            $follow_ids = $follower->followingIds($user->id);

            // $follow_idsの中にはfollowing_id（フォローしているユーザーID、つまりログインユーザーのID）も含まれるためpluckでfollowed_idカラムのみ配列で取得
            $following_ids = $follow_ids->pluck('followed_id')->toArray();

            // 
            $logs_data = $log->getFollowingTimelines($user->id, $following_ids);
        } else {
            // 
            $logs_data = $log->getAllTimelines();
        }

        return $logs_data;
    }

    /**
     * 指定したユーザーのログ取得
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function userLog(Log $log, $user_id)
    {
        // logListで使用するlogsオブジェクトに入るデータの取得
        $user_logs_data = $log->getUserTimelines($user_id);

        return $user_logs_data;
    }

    /**
     * ログ作成（空のログとして保存）
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function store(LogRequest $request, Log $log)
    {
        // ログインしているユーザーのログとして保存（ログインしなければ保存できない）
        $user = auth()->user();

        $log->user_id = $user->id;
        $log->title = $request->input('title');

        $log->save();

        // CreateLog.vueでlogIdを保持するため
        return response($log->id, 201);
    }

    /**
     * ログ詳細取得
     * @param Models\Log
     * @return Response
     */
    public function show(Log $log)
    {
        $log_data = $log->with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // 種目ログの詳細データ
            $query->with('event');
        }])
        // findだと上手くデータ取得できない？
        ->where('id', $log->id)->first();

        return $log_data ?? abort(404);
    }

    /**
     * ログアップデート
     * @param int $log
     * @return Response
     */
    public function update(LogRequest $request, Log $log)
    {
        $log->fill($request->all())->save();

        return response('', 200);
    }

    /**
     * ログ削除
     * 
     * 
     */
    public function destroy(Log $log)
    {
        $log->delete();

        // レスポンスコード200(ok)を返却
        return response('', 200);
    }
}
