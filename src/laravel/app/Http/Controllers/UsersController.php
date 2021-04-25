<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Log;
use App\Models\Follower;

class UsersController extends Controller
{
    /**
     * 全ユーザーのデータを返す（10人ずつ、フロントで無限スクロール実装）
     * @param App\Models\User $user
     * @return array  $all_user_datas（ユーザーのフォロー状況等）,$users（ページネーションで取得したユーザー情報）
     */
    public function index(User $user)
    {
        $users = $user->orderBy(User::CREATED_AT, 'desc')->paginate(10);

        $login_user = auth()->user();

        $all_user_datas = [];

        foreach ($users as $user) {
            // $is_followingはログインユーザーがフォローしているユーザー情報
            $is_following = $login_user->isFollowing($user->id);
            // $is_followedはログインユーザーをフォローしているユーザー情報
            $is_followed = $login_user->isFollowed($user->id);

            $all_user_datas = array_merge($all_user_datas, array([
                'user_data'      => $user,
                'is_following'   => $is_following,
                'is_followed'    => $is_followed,
            ]));
        }
        return response(['data' => $all_user_datas, 'users' => $users], 200);
    }

    /**
     * 指定したユーザーの情報を返す
     * @param App\Models\User $user
     * @param App\Models\Log $log
     * @param App\Models\Follower $follower
     * @return array 
     */
    public function show(User $user, Log $log, Follower $follower)
    {
        $user_data = $user->where('id', $user->id)
            ->with(['logs' => function($query){
                $query->with(['user', 'favorites', 'comments','event_logs' => function($query){
                    $query->with('event');
                }]);
            }])->first();
        
        // $login_userはログインしている自身の情報
        $login_user = auth()->user();
        // $is_followingはログインユーザーがフォローしているユーザー情報
        $is_following = $login_user->isFollowing($user->id);
        // $is_followedはログインユーザーをフォローしているユーザー情報
        $is_followed = $login_user->isFollowed($user->id);
        // $~~countとついてるのがそれぞれのデータのカウント関連
        $log_count = $log->getLogCount($user->id);
        $follow_count = $follower->getFollowCount($user->id);
        $follower_count = $follower->getFollowerCount($user->id);

        return [
            'user_data'      => $user_data,
            'is_following'   => $is_following,
            'is_followed'    => $is_followed,
            'log_count'      => $log_count,
            'follow_count'   => $follow_count,
            'follower_count' => $follower_count
        ] ?? abort(404);
    }

    /**
     * ユーザープロフィール更新
     * @param App\Http\Requests\UserRequest バリーデートしたリクエスト
     * @param App\Models\User $use
     * @return void
     */
    public function update(UserRequest $request, User $user)
    {
        $login_user = auth()->user();

        $data = $request->all();

        $user->updateProfile($data, $login_user);

        return response('', 200);
    }

    /**
     * 指定したユーザーをログインユーザーがフォローする
     * @param App\Models\User $use
     * @return void
     */
    public function follow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if (!$is_following) {
            $follower->follow($user->id);
            return response('', 201);
        }

        return abort(404);
    }

    /**
     * 指定したユーザーをログインユーザーがフォロー解除する
     * @param App\Models\User $use
     * @return void
     */
    public function unfollow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if ($is_following) {
            $follower->unfollow($user->id);
            return response('', 200);
        }

        return abort(404);
    }
}