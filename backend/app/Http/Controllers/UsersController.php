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
        $user_data = User::with('events')->where('id', $user_id)->first();
        // ->where('id', $user_id)->first();

        return $user_data;
    }

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