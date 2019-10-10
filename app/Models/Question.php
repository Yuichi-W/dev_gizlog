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
        $questions = $query;
        if(!empty($inputs['search_word'])) {
            $questions = $query->where('title', 'like', '%' .$inputs['search_word']. '%');
        } 
        if(!empty($inputs['tag_category_id'])) {
            $questions = $query->where('tag_category_id', $inputs['tag_category_id']);
        }
        return $questions->orderby('created_at', 'desc')->with(['user', 'tagCategory', 'comments']);
    }

    public function scopeSearchingUserQuestion($query, $userId)
    {
        return $query->where('user_id', $userId)->latest();
    }
}

