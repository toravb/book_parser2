<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAudioBookRatingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_book_id' => ['required', 'integer', 'exists:audio_books,id',
                Rule::unique('rates')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'rating' => ['required', 'numeric', 'between:1,5'],


        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
