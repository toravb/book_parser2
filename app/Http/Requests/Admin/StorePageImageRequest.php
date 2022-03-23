<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePageImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'image']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
