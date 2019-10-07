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
        return $this->belongsTo(TagCategory::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function searchingQuestion($inputs)
    {
        if (!empty($inputs['search_word'])) {
            $questions = $this->searchingWordQuestion($inputs['search_word'])->get();
            if (!empty($inputs['tag_category_id'])) {
                $questions = $this->searchingWordQuestion($inputs['search_word'])->searchingCategoryQuestion($inputs['tag_category_id'])->get();
            }
        } else {
            $questions = $this->all()->sortByDesc('created_at');
            if (!empty($inputs['tag_category_id'])) {
                $questions = $this->searchingCategoryQuestion($inputs['tag_category_id'])->get();
            } 
        }
        return $questions;
    }

    public function scopeSearchingWordQuestion($query, $keyword) 
    {
        return $query->where('title', 'like', '%' .$keyword. '%')->orderBy('created_at', 'desc');
    }

    public function scopeSearchingCategoryQuestion($query, $id)
    {
        return $query->where('tag_category_id', $id )->orderBy('created_at', 'desc');
    }

    public function scopSearchingUserQuestion($query, $userId)
    {
        return $query->where('user_id', $userId)->orderBy('created_at', 'desc');
    }
}

