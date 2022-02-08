<?php

namespace App\Api\Http\Requests;

use App\Models\AudioBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateChangeAudioBookStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_book_id' => ['required', 'integer', Rule::exists(AudioBook::class, 'id')],
            'status' => ['required', 'integer', Rule::in(AudioBook::$availableListeningStatuses)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
