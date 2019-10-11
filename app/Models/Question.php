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

    public function searchingQuestion($inputs)
    {
        return $this->seachingWordQuestion($inputs)
                    ->seachingCategoryQuestion($inputs)
                    ->latest()
                    ->with(['user', 'tagCategory', 'comments']);
    }

    public function scopeSeachingWordQuestion($query, $inputs)
    {
        if (!empty($inputs['search_word'])) {
            $query->where('title', 'like', '%' .$inputs['search_word']. '%');
        } 
    }

    public function scopeSeachingCategoryQuestion($query, $inputs)
    {
        if (!empty($inputs['tag_category_id'])) {
            $query->where('tag_category_id', $inputs['tag_category_id']);
        } 
    }

    public function scopeSearchingUserQuestion($query, $userId)
    {
        return $query->where('user_id', $userId)->latest();
    }
}

