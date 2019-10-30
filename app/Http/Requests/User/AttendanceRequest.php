<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'revision_request' => 'sometimes|required|max:500',
            'absent_reason'    => 'sometimes|required|max:500',
            'date'             => 'sometimes|required|before:now',
        ];
    }

    public function messages()
    {
        return [
            'date_time.before' => '今日以前の日付を入力してください',
            'required'         => '入力必須の項目です',
            'max'              => ':max文字以内で入力してください。',
        ];
    }
}

