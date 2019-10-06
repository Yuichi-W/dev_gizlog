<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\TagCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use softDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'user_id',
        'question_id',
        'tag_category_id',
        'title',
        'content',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tagCategory() 
    {
        return $this->belongsTo(TagCategory::class, 'tag_category_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'question_id');
    }

    public function searchingQuestion($inputs)
    {
        if (!empty($inputs['search_word'])) {
            $questions = $this->searchingWordQuestion($inputs['search_word']);
        } elseif (!empty($inputs['tag_category_id'])) {
            $questions = $this->searchingCategoryQuestion($inputs['tag_category_id']);
        }else {
            $questions = $this->all()->sortByDesc('created_at');
        }
        return $questions;
    }

    public function searchingWordQuestion($keyword)
    {
        $builder = $this->newQuery(); 
        $builder->where('title', 'like', '%' .$keyword. '%');
        $builder->orderBy('created_at', 'desc');
        return $builder->get();
    }

    public function searchingCategoryQuestion($id)
    {
        $builder = $this->newQuery();
        $builder->where('tag_category_id', $id );
        $builder->orderBy('created_at', 'desc');
        return $builder->get();
    }

    public function searchingUserQuestion($userId)
    {
        $builder = $this->newQuery();
        $builder->where('user_id', $userId);
        $builder->orderBy('created_at', 'desc');
        return $builder->get();
    }
}

