<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FileDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_path' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
