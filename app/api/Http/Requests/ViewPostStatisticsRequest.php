<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ViewPostStatisticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array("viewPostStatistics", Auth::user()->accountType->permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'sometimes|integer',
            'sortBy ' => [
                'bail',
                'nullable',
                'string',
                'in:likes_count,comments_count,favorite_count,reposts_count,
                unlogged_views_count,logged_in_views_count,created_at'
            ],
            'per_page' => 'nullable|max:50'
        ];
    }
}
