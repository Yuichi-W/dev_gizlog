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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeSearchingQuestion($query, $inputs)
    {
        if(!empty($inputs)) {
            $questions = $query->where('title', 'like', '%' .$inputs['search_word']. '%');
            if(!empty($inputs['tag_category_id'])) {
                $questions->where('tag_category_id', $inputs['tag_category_id']);
            }
            $questions = $questions->latest()->get();
        } else {
            $questions = $this->all()->sortByDesc('created_at');
        }
        return $questions;
    }

    public function scopeSearchingUserQuestion($query, $userId)
    {
        return $query->where('user_id', $userId)->latest();
    }
}

