<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Comment;
use App\Models\TagCategory;
use App\Http\Requests\User\QuestionsRequest;
use Auth;

class QuestionController extends Controller
{

    protected $question;
    protected $comment;
    protected $tagCategory;

    public function __construct(Question $question, Comment $comment, TagCategory $tagCategory) 
    {
        $this->middleware('auth');
        $this->question = $question;
        $this->comment = $comment;
        $this->tagCategory = $tagCategory;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $inputs = $request->all();
        $categories = $this->tagCategory->all();
        $questions = $this->question->searchingQuestion($inputs);
        return view('user.question.index', compact('inputs', 'categories', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('user.question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  QuestionsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(QuestionsRequest $request)
    {
        $inputs = $request->all();
        $this->question->create($inputs);
        return redirect()->to('question');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $question = $this->question->find($id);
        return view('user.question.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $question = $this->question->find($id);
        return view('user.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  QuestionsRequest  $request
     * @param  int  $question_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QuestionsRequest $request, $id)
    {
        $inputs = $request->all();
        $this->question->find($id)->fill($inputs)->save();
        return redirect()->to('question');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->question->find($id)->delete();
        return redirect()->to('question');
    }

    /**
     *
     * @param  QuestionsRequest  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm(QuestionsRequest $request)
    {
        $inputs = $request->all();
        $categoryName = $this->tagCategory->find($request->tag_category_id)->name;
        return view('user.question.confirm', compact('inputs', 'categoryName'));
    }

    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mypage(Request $request)
    {
        $questions = $this->question->searchingUserQuestion(Auth::id());
        $inputs = $request->all();
        $category = $this->tagCategory->all();
        return view('user.question.mypage', compact('inputs', 'category', 'questions'));
    }
}
