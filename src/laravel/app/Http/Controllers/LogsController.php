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
     * @param App\Models\Log $log
     * @param App\Models\Follower $follower
     * @return array $logs_data
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
            $logs_data = $log->getFollowingTimelines($user->id, $following_ids)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        } else {
            // 
            $logs_data = $log->getAllTimelines()
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        }

        return $logs_data;
    }

    /**
     * 指定したユーザーのログ取得
     * @param App\Models\Log $log
     * @param integer $user_id 指定したユーザーのid
     * @return array $user_logs_data
     */
    public function userLog(Log $log, $user_id)
    {
        // logListで使用するlogsオブジェクトに入るデータの取得
        $user_logs_data = $log->getUserTimelines($user_id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return $user_logs_data;
    }

    /**
     * ログ作成
     * @param App\Http\Requests\LogRequest $request バリデーション後のリクエスト
     * @param App\Models\Log $log
     * @return integer $log->id 
     */
    public function store(LogRequest $request, Log $log)
    {
        // ログインしているユーザーのログとして保存（ログインしなければ保存できない）
        $user = auth()->user();

        if ($request->user_id !== $user->id) {
            return abort(404);
        }

        $log->user_id = $user->id;
        $log->title = $request->input('title');

        $log->save();

        // CreateLog.vueでlogIdを保持するため
        return response($log->id, 201);
    }

    /**
     * ログ詳細取得
     * @param App\Models\Log $log
     * @return array $log_data
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
     * @param App\Http\Requests\LogRequest $request バリデーション後のリクエスト
     * @param App\Models\Log $log 
     * @return void
     */
    public function update(LogRequest $request, Log $log)
    {
        // ログインしているユーザーのログとして保存（ログインしなければ保存できない）
        $user = auth()->user();

        if ($request->user_id !== $user->id) {
            return abort(404);
        }
        
        $log->fill($request->validated())->save();

        return response('', 200);
    }

    /**
     * ログ削除
     * @param App\Models\Log $log 
     * @return void
     */
    public function destroy(Log $log)
    {
        $log->delete();

        // レスポンスコード200(ok)を返却
        return response('', 200);
    }
}
