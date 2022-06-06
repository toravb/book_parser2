<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReadingStatusStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:pages,book_id'],
            'page_number' => [
                'required',
                'numeric',
                Rule::exists('pages', 'page_number')->where('book_id', $this->route()->parameter('id')),
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
