<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAudioBookFromUsersListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_book_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
