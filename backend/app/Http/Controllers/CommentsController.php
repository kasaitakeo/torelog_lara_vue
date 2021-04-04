<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
