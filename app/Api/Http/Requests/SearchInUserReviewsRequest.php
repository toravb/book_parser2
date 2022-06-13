<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchInUserReviewsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'findByTitle' => ['sometimes', 'string', 'max:200']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
