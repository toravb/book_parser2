<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ViewEventStatisticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array("viewEventStatistics", Auth::user()->accountType->permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sortBy'   => [
                'sometimes',
                'string',
                Rule::in([
                    'likes_count',
                    'comments_count',
                    'favorite_count',
                    'reposts_count',
                    'card_unlogged_views_count',
                    'card_logged_in_views_count',
                    'page_unlogged_views_count',
                    'page_logged_in_views_count'
                ])
            ],
            'sortDesc' => 'sometimes|boolean',
            'per_page' => 'sometimes|integer|max:40',
            'page'     => 'sometimes|integer'
        ];
    }
}
