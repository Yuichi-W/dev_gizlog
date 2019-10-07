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
            'title'   => 'required|max:30',
            'content' => 'required|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'required'    => '入力必須項目です',
            'title.max'   => '30文字以内で入力してください',
            'content.max' => '1000文字以内で入力してください',
        ];
    }
}

