<?php

namespace App\Http\Requests;

use App\Models\ReviewType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReviewTypeRequest extends StoreReviewTypeRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'int', Rule::exists(ReviewType::class)],
            'type' => ['required', 'string', Rule::unique(ReviewType::class)->ignore($this->id)]
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
