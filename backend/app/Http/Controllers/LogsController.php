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
     * 
     * 
     */
    public function index(Follower $follower)
    {
        $logs = Log::with(['user', 'favorites', 'comments'])
        ->orderBy(Log::CREATED_AT, 'desc')->paginate();

        return $logs;
        // return $log->all();
    }
    // /**
    //  * ログ一覧取得
    //  * 
    //  * 
    //  */
    // public function index(Log $log, Follower $follower)
    // {
    //     // ログインユーザー取得
    //     $user = auth()->user();

    //     // Models\Followerで定義した関数followingIdsでログインユーザーがフォローしているユーザーを取得(array)
    //     $follow_ids = $follower->followingIds($user->id);

    //     // followed_idだけ抜き出す following_idは自分のidでいらないから->そもそも配列で取得した時点でfollowed_idのみ取り出せているのでは？
    //     // $following_ids = $follow_ids->pluck('followed_id')->toArray();

    //     // Models/Logで定義したgetTimelinesでログインユーザーがフォローしているユーザーのログのみ取得
    //     $timelines = $log->getTimelines($user->id, $follow_ids);

    //     return $timelines;
    // }

    /**
     * ログ作成
     * @param Request
     * @param Models\Log
     * @return Response
     */
    public function store(Log $log)
    {
        // $user = Auth::user();
        $user = auth()->user();
        
        $log->user_id = $user->id;
        $log->save();

        return $log->id;
    }

    /**
     * ログ詳細取得
     * 
     * 
     */
    public function show($log_id)
    {
        $log_data = Log::with(['user', 'favorites', 'comments'])
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
        // $user = $request->user();
        // dd($user);

        // requestのデータ(collect型)をall()を使用し配列で取得する
        $data = $request->all();

        // Validatorファザードでバリデーションを実行するにはmakeメソッド(新しいコレクションを作成)を使用してインスタンスを作成
        // 引数はValidator::make('値の配列', '検証ルールの配列')で記述
        $validator = Validator::make($data, [
            'text' => ['required', 'string', 'max:300']
        ]);

        // validateメソッドは、POSTされた値のバリデーションに成功するとコードは通常通り続けて実行され、バリデーションに失敗すると自動的に例外が投げられユーザーへ適切なエラーメッセージが返されるメソッド
        $validator->validate();
        
        // logモデルのlogStore()メソッドでrequestされたdataを保存
        $log->logStore($user->id, $data);

        // レスポンスコード201(created)を返却
        $log->update($request->all());

        return $log;
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

        return $log;
    }
}
