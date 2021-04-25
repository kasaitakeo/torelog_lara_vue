<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * 指定したidのログへのコメント登録
     * @param  App\Http\Requests\CommentRequest $request
     * @param  App\Models\Comment $comment
     * @return void
     */
    public function store(CommentRequest $request, Comment $comment)
    {
        $user = auth()->user();

        $comment->user_id = $user->id;
        $comment->log_id = $request->input('log_id');
        $comment->text = $request->input('text');

        $comment->save();

        return response('', 201);
    }
}
