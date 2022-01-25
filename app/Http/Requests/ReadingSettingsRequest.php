<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReadingSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'isTwoColumns' => ['required', 'boolean'],
            'fontSize' => ['required', 'integer', 'between:0,10'],
            'screenBrightness' => ['required', 'integer', 'between:0,10'],
            'fontName' => ['required', 'string',
                Rule::in(['Times New Roman', 'Georgia', 'Arial', 'Ubuntu', 'Verdana',])],
            'fieldSize' => ['required', 'integer', 'between:0,10'],
            'rowHeight' => ['required', 'integer', 'between:0,10'],
            'isCenterAlignment' => ['required', 'boolean']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
