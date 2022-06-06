<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReadingStatusShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('reading_statuses', 'book_id')->where('user_id', auth('api')->id())
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
