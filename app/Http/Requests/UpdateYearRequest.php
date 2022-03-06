<?php

namespace App\Http\Requests;

use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateYearRequest extends StoreYearRequest
{
    public function rules(): array
    {
        $rules = [
          'id' => ['required', 'int', Rule::exists(Year::class)],
          'year' => ['required', 'int', Rule::unique(Year::class)->ignore($this->id)]
        ];
        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
