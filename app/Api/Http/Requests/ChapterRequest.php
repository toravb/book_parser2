<?php

namespace App\Api\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChapterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
          //  'title' => ['sometimes', 'string', 'max:200']
        ];
    }

    public function authorize(): bool
    {
        return true;

    }
}
