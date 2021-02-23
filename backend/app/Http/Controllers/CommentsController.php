<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // ツイートの保存と同じ流れ
    public function store(Request $request, Comment $comment)
    {
        $user = auth()->user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'log_id' =>['required', 'integer'],
            'text'     => ['required', 'string', 'max:140']
        ]);

        $validator->validate();
        $comment->commentStore($user->id, $data);

        return back();
    }
}
