<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function rules(): array
    {
        (int)$this->request->get('status');

        return [
            'title' => ['required', 'string', 'min:8',],
            'description' => ['required', 'string', 'max:10240'],
            'cover-image' => ['required', 'file', 'max:3072',],
            'book-file' => ['required', 'file', 'max:10072'],
            'status' => ['required', 'digits_between:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
