<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowBooksFilterByLetterRequest extends FormRequest
{
    protected $types = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->types = array_keys($types->getBookTypes());
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types)],
            'alphabetTitleIndex' => ['sometimes', 'string', 'alpha'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
