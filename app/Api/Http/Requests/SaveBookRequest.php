<?php

namespace App\Api\Http\Requests;

use App\Api\Http\Controllers\BookController;
use App\Models\Book;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SaveBookRequest extends FormRequest
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
            'book_id' => ['required', 'integer', 'exists:books,id',
                Rule::unique('book_user')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'status' => ['required', 'integer',
                Rule::in(
                    Book::WANT_READ,
                    Book::READING,
                    Book::HAD_READ)],

        ];
    }
}
