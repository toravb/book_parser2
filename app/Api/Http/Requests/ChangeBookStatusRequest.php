<?php

namespace App\Api\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeBookStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'book_id' => ['required', 'integer', Rule::exists(Book::class, 'id')],
            'status' => ['required', 'integer', Rule::in(Book::$availableReadingStatuses)],
        ];
    }
}
