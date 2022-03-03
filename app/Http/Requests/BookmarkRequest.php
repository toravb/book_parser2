<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookmarkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', Rule::exists(Book::class, 'id')],
            'page_id' => ['required', 'integer', Rule::exists(Page::class, 'id')->where('book_id', $this->book_id)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
