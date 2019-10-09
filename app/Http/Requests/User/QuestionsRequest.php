<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tag_category_id' => 'required|integer|',
            'title'           => 'required|max:50',
            'content'         => 'required|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'required'                         => '入力必須項目です',
            'tag_category_id.required'         => 'カテゴリーを選択してください',
            'title.max'                        => '50文字以内で入力してください',
            'content.max'                      => '1000文字以内で入力してください',
        ];
    }
}

