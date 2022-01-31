<?php

namespace App\Api\Http\Requests;

use App\Api\Services\TypesGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetAudioBooksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', Rule::in(array_keys((new TypesGenerator())->getCompilationsBookTypes()))],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
