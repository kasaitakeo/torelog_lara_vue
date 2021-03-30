<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Log;
use App\Models\Follower;

class UsersController extends Controller
{
    //
    public function index(User $user)
    {
        return $user->all();
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
        $is_following = $login_user->isFollowing($user->id);
        $is_followed = $login_user->isFollowed($user->id);
        // $timelinesはユーザのツイート情報
        // $timelines = $log->getUserTimeLine($user->id);
        // $~~countってついてるのがカウント関連
        $log_count = $log->getLogCount($user->id);
        $follow_count = $follower->getFollowCount($user->id);
        $follower_count = $follower->getFollowerCount($user->id);

        return [
            'user_data'      => $user_data,
            'is_following'   => $is_following,
            'is_followed'    => $is_followed,
            // 'timelines'      => $timelines,
            'log_count'    => $log_count,
            'follow_count'   => $follow_count,
            'follower_count' => $follower_count
        ];
        
    }

    public function update(Request $request, User $user)
    {
        $login_user = auth()->user();

        $data = $request->all();

        // Rule::unique('users')->ignore($login_user->id)の部分はユニークに設定しているscreen_name,emailを自身のIDの時だけユニーク無効にするという設定
        $validator = Validator::make($data, [
            'screen_name'   => ['required', 'string', 'max:50', Rule::unique('users')->ignore($login_user->id)],
            'name'          => ['required', 'string', 'max:255'],
            'user_text'          => ['required', 'string', 'max:255'],
            'profile_image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($login_user->id)]
        ]);

        $validator->validate();

        $user->updateProfile($data, $login_user);

        return response('', 201);
    }

    public function follow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if (!$is_following) {
            $follower->follow($user->id);
            return;
        }
    }
    public function unfollow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if ($is_following) {
            $follower->unfollow($user->id);
            return;
        }
    }
}