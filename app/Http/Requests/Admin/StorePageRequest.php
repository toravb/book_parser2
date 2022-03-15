<?php

namespace App\Http\Requests\Admin;

use App\Models\Book;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => ['required', 'int', Rule::exists(Book::class, 'id')],
            'content' => ['required', 'string', 'max:64000'],
            'page_number' => [
                'required',
                'int',
                'min:0',
                Rule::unique(Page::class)->where('book_id', $this->book_id)
            ],
        ];
    }

    public function messages()
    {
        return [
          'page_number.unique' => 'Страница с таким порядковы номером для этой книги уже существует'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
