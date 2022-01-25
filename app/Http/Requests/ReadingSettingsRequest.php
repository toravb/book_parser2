<?php

namespace App\Http\Requests;

use App\Models\ReadingSettings;
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
                Rule::in(ReadingSettings::$fonts)],
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
