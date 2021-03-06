<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Log;
use App\Models\Follower;
use App\Models\Comment;
use App\Models\Event;

class LogsController extends Controller
{
    /**
     * ログ一覧取得
     */
    public function index(Log $log)
    {
        $logs = $log->with(['user', 'favorites', 'comments','event_logs' => function($query){
            $query->with('event');
        }])->orderBy(Log::CREATED_AT, 'desc')
        ->paginate();

        return $logs;
    }

    /**
     * ログ作成
     * @param Request
     * @param Models\Log
     * @return Response
     */
    public function store(Log $log)
    {
        $user = auth()->user();
        
        $log->user_id = $user->id;

        $log->save();

        // CreateLog.vueでlogIdを保持するため
        return $log->id;
    }

    /**
     * ログ詳細取得
     * 
     * 
     */
    public function show($log_id)
    {
        $log_data = Log::with(['user', 'favorites', 'comments', 'event_logs' => function($query){
            $query->with('event');
        }])
        // findだと上手くデータ取得できない
        // ->find((int)$log_id)->first();
        ->where('id', $log_id)->first();

        return $log_data;
    }

    /**
     * ログアップデート
     * @param int $log
     * @return Response
     */
    public function update(Request $request, Log $log)
    {
        $user = auth()->user();

        $text = $request->input('text');

        // Validatorファザードでバリデーションを実行するにはmakeメソッド(新しいコレクションを作成)を使用してインスタンスを作成
        // 引数はValidator::make('値の配列', '検証ルールの配列')で記述
        // $validator = Validator::make($text, [
        //     'text' => ['required', 'string', 'max:300']
        // ]);

        // validateメソッドは、POSTされた値のバリデーションに成功するとコードは通常通り続けて実行され、バリデーションに失敗すると自動的に例外が投げられユーザーへ適切なエラーメッセージが返されるメソッド
        // $validator->validate();
        
        // logモデルのlogStore()メソッドでrequestされたdataを保存
        // $log->logStore($user->id, $data);
        $log->text = $text;

        $log->save();

        // レスポンスコード201(created)を返却
        // $log->update($request->all());

        return;
    }

    /**
     * ログ削除
     * 
     * 
     */
    public function destroy(Log $log)
    {
        // $log->logDestroy($log->id);
        $log->delete();

        return;
    }
}
