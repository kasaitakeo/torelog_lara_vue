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
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function index(Log $log)
    {
        // logListで使用するlogsオブジェクトに入るデータの取得
        $logs_data = $log->with(['user', 'favorites', 'comments' => function($query){
            // コメントしたユーザー情報
            $query->with('user');
        }, 'event_logs' => function($query){
            // ログに追加されている種目の情報
            $query->with('event');
        }])->orderBy(Log::CREATED_AT, 'desc')
        ->paginate(15);

        return response($logs_data, 200);
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
            $query->with('user');
        }, 'event_logs' => function($query){
            $query->with('event');
        }])
        // findだと上手くデータ取得できない？
        ->where('id', $log->id)->first();

        return response($log_data, 200);
    }
    // public function show($id)
    // {
    //     $log = Log::with(['user', 'favorites', 'comments' => function($query){
    //         $query->with('user');
    //     }, 'event_logs' => function($query){
    //         $query->with('event');
    //     }])
    //     // findだと上手くデータ取得できない
    //     // ->find((int)$log_id)->first();
    //     ->where('id', $id)->first();

    //     return $log;
    // }

    /**
     * ログアップデート
     * @param int $log
     * @return Response
     */
    public function update(Request $request, Log $log)
    {
        $user = auth()->user();

        $log->text = $request->input('text');

        // Validatorファザードでバリデーションを実行するにはmakeメソッド(新しいコレクションを作成)を使用してインスタンスを作成
        // 引数はValidator::make('値の配列', '検証ルールの配列')で記述
        // textは必須ではないので'required'は検証ルールに入れない
        $validator = Validator::make($request->all(), [
            'text' => ['required', 'string', 'max:140']
        ]);

        // validateメソッドは、POSTされた値のバリデーションに成功するとコードは通常通り続けて実行され、バリデーションに失敗すると自動的に例外が投げられユーザーへ適切なエラーメッセージが返されるメソッド
        $validator->validate();
        
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
        // $log->logDestroy($log->id);
        $log->delete();

        return;
    }
}
