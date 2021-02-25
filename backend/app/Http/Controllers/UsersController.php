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

    public function show($user_id)
    {
        $user_data = User::with('events', 'logs')->where('id', $user_id)->first();
        // ->where('id', $user_id)->first();

        return $user_data;
        
    }

    // public function show(User $user, Tweet $tweet, Follower $follower)
    // {
    //     // $login_userはログインしている自身の情報
    //     $login_user = auth()->user();
    //     $is_following = $login_user->isFollowing($user->id);
    //     $is_followed = $login_user->isFollowed($user->id);
    //     // $timelinesはユーザのツイート情報
    //     $timelines = $tweet->getUserTimeLine($user->id);
    //     // $~~countってついてるのがカウント関連
    //     $tweet_count = $tweet->getTweetCount($user->id);
    //     $follow_count = $follower->getFollowCount($user->id);
    //     $follower_count = $follower->getFollowerCount($user->id);

    //     return [
    //         'user'           => $user,
    //         'is_following'   => $is_following,
    //         'is_followed'    => $is_followed,
    //         'timelines'      => $timelines,
    //         'tweet_count'    => $tweet_count,
    //         'follow_count'   => $follow_count,
    //         'follower_count' => $follower_count
    //     ];
    // }

    public function update(Request $request,User $user)
    {
        // $login_user = auth()->user();

        // $data = $request->all();

        // // Rule::unique('users')->ignore($user->id)の部分はユニークに設定しているscreen_nameやemailを自身のIDの時だけ無効にするという設定
        // $validator = Validator::make($data, [
        //     'screen_name'   => ['required', 'string', 'max:50', Rule::unique('users')->ignore($login_user->id)],
        //     'name'          => ['required', 'string', 'max:255'],
        //     'profile_image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        //     'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($login_user->id)]
        // ]);

        // $validator->validate();

        // $user->updateProfile($data);
        $user->update($request->all());
        
        return $user;
    }

    public function follow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if (!$is_following) {
            $folower->follow($user->id);
            return;
        }
    }
    public function unfollow(User $user)
    {
        $follower = auth()->user();

        $is_following = $follower->isFollowing($user->id);

        if ($is_following) {
            $folower->unfollow($user->id);
            return;
        }
    }
}