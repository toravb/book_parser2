<?php

namespace App\Api\Http\Requests;

use App\Models\ReadingSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReadingSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'is_two_columns' => ['required', 'boolean'],
            'font_size' => ['required', 'integer', 'between:0,10'],
            'screen_brightness' => ['required', 'integer', 'between:0,10'],
            'font_name' => [
                'required',
                'string',
                Rule::in(ReadingSettings::$fonts)
            ],
            'field_size' => ['required', 'integer', 'between:0,10'],
            'row_height' => ['required', 'integer', 'between:0,10'],
            'is_center_alignment' => ['required', 'boolean']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
