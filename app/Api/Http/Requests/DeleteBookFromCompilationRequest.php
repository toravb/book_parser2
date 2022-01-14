<?php

namespace App\Api\Http\Requests;

use App\Api\Http\Controllers\BookController;
use App\Api\Rules\CheckBookFromCompilationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            'compilation_id' => ['bail', 'required', 'integer', 'exists:compilations,id',
                Rule::exists('compilation_user')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
                ],
            'book_id' => ['bail', 'required', 'integer','exists:book_compilation,compilationable_id',
                Rule::exists('book_compilation', 'compilationable_id')->where(function ($query) {
                    return $query->where('compilation_id', $this->compilation_id);
                }),
                ],
            'book_type' => ['bail', 'required', 'string',
                Rule::in(
                    BookController::TYPE_BOOK,
                    BookController::TYPE_AUDIO_BOOK)],
        ];
    }
}
