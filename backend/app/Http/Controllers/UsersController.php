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
    //
    public function index(User $user)
    {
        $all_user = $user->orderBy(User::CREATED_AT, 'desc')->paginate(10);

        return response($all_user, 200);
    }

    public function show(User $user, Log $log, Follower $follower)
    {
        $user_data = User::with(['logs' => function($query){
            $query->with(['user', 'favorites', 'comments','event_logs' => function($query){
                $query->with('event');
            }]);
        }])->where('id', $user->id)->first();
        
        // $login_userはログインしている自身の情報
        $login_user = auth()->user();
        // $is_followingはログインユーザーがフォローしているユーザー情報
        $is_following = $login_user->isFollowing($user->id);
        // $is_followedはログインユーザーをフォローしているユーザー情報
        $is_followed = $login_user->isFollowed($user->id);
        // $~~countってついてるのがそれぞれのデータのカウント関連
        $log_count = $log->getLogCount($user->id);
        $follow_count = $follower->getFollowCount($user->id);
        $follower_count = $follower->getFollowerCount($user->id);

        return response([
            'user_data'      => $user_data,
            'is_following'   => $is_following,
            'is_followed'    => $is_followed,
            'log_count'      => $log_count,
            'follow_count'   => $follow_count,
            'follower_count' => $follower_count
        ], 200);
        
    }

    public function update(UserRequest $request, User $user)
    {
        $login_user = auth()->user();

        $data = $request->all();

        $user->updateProfile($data, $login_user);

        return response('', 201);
    }

    public function follow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if (!$is_following) {
            $follower->follow($user->id);
            return response('', 201);
        }
    }
    public function unfollow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if ($is_following) {
            $follower->unfollow($user->id);
            return response('', 201);
        }
    }
}