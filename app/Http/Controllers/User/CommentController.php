<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CommentRequest;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->middleware('auth');
        $this->comment = $comment;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request)
    {
        // dd($request->question_id);
        $inputs = $request->all();
        // dd($inputs);
        // $inputs['user_id'] = Auth::id();
        // dd($this->comment->create($inputs));
        $this->comment->create($inputs);
        return redirect()->route('question.show', $request->question_id);
    }

}
