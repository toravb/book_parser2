<?php

namespace App\Http\Requests;

use App\Models\ReviewType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::unique(ReviewType::class)]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
