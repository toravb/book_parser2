<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ViewPostRegisterRequest extends FormRequest
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
            'id' => [
                'bail',
                'required',
                Rule::exists('posts')->where(function ($query) {
                    $query->where('type', 'post');
                }),
                Rule::unique('statistics_posts_user', 'statistics_posts_id')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ]
        ];
    }
}
