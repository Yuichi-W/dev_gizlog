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
    const DEFAULT_SELECT = 'Select category';

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
        $questions = $this->question->searchingQuestion($inputs)->paginate(100);
        return view('user.question.index', compact('inputs', 'categories', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->tagCategory->all();
        $categoryArray = $this->makeSelectValue($categories);
        return view('user.question.create', compact('categoryArray'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  QuestionsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(QuestionsRequest $request)
    {
        $userId = Auth::id();
        $request['user_id'] = $userId;
        $inputs = $request->all();
        $this->question->create($inputs);
        return redirect()->route('question.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $question = $this->question->with('comments.user')->find($id);
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
        $categories = $this->tagCategory->all();
        $categoryArray = $this->makeSelectValue($categories);
        return view('user.question.edit', compact('question', 'categoryArray'));
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
        return redirect()->route('question.index');
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
        return redirect()->route('question.index');
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
        $questions = $this->question->searchingUserQuestion(Auth::id())->with(['user', 'tagCategory', 'comments'])->get();
        $inputs = $request->all();
        $category = $this->tagCategory->all();
        return view('user.question.mypage', compact('inputs', 'category', 'questions'));
    }

    public function makeSelectValue($categories)
    {
        return $categories
                    ->pluck('name', 'id')
                    ->prepend(self::DEFAULT_SELECT, '')
                    ->all();

    }
}
