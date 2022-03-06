<?php

namespace App\Http\Requests;

use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreYearRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'year' => ['required', 'int', Rule::unique(Year::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
