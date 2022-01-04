<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ViewPhotoStatisticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array("viewPhotoStatistics", Auth::user()->accountType->permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sortBy' => 'sometimes|string|in:likes_count,comments_count,favorite_count,reposts_count',
            'sortDesc' => 'sometimes|boolean',
            'per_page' => 'sometimes|integer|max:40',
            'page' => 'sometimes|integer'
        ];
    }
}
