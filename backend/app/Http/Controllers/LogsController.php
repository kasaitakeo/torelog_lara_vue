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
    public function index()
    {
        // logListで使用するlogsオブジェクトに入るデータの取得
        $logs_data = Log::with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->orderBy(Log::CREATED_AT, 'desc')
        ->paginate(10);

        return response($logs_data, 200);
    }

    /**
     * ログ一覧取得
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function userLog($user_id)
    {
        // logListで使用するlogsオブジェクトに入るデータの取得
        $user_logs_data = Log::with(['user', 'favorites', 'comments' => function($query){
            // コメントした全ユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->where('user_id', $user_id)
        ->orderBy(Log::CREATED_AT, 'desc')
        ->paginate(10);

        return response($user_logs_data, 200);
    }

    /**
     * ログ作成（空のログとして保存）
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function store(Log $log)
    {
        // ログインしているユーザーのログとして保存（ログインしなければ保存できない）
        $user = auth()->user();

        $log->user_id = $user->id;

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

        return response($log_data, 200);
    }

    /**
     * ログアップデート
     * @param int $log
     * @return Response
     */
    public function update(LogRequest $request, Log $log)
    {
        $user = auth()->user();

        $log->text = $request->input('text');
        
        $log->save();

        // レスポンスコード201(created)を返却
        return response('', 201);
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
