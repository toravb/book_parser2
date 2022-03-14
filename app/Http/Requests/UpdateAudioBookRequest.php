<?php

namespace App\Http\Requests;

use App\Models\AudioBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAudioBookRequest extends StoreAudioBookRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'int', Rule::exists(AudioBook::class)],
            'alias_url' => ['nullable', 'string', 'max:1000', Rule::unique(AudioBook::class)->ignore($this->id)],
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
