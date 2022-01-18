<?php

namespace App\Api\Http\Requests;

use App\Api\Http\Controllers\BookController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteBookFromCompilationRequest extends FormRequest
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
            'compilation_id' => ['bail', 'required', 'integer'],
            'book_id' => ['bail', 'required', 'integer'],
            'book_type' => ['bail', 'required', 'string',
                Rule::in(
                    BookController::TYPE_BOOK,
                    BookController::TYPE_AUDIO_BOOK)],
        ];
    }
}
