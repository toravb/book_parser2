<?php

namespace App\Api\Http\Requests;

use App\Api\Filters\QueryFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoveltiesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sortBy' => ['required', Rule::in([
                QueryFilter::SORT_BY_DATE,
                QueryFilter::BESTSELLERS
            ])],
            'type' => ['required', Rule::in([
                QueryFilter::TYPE_BOOK,
                QueryFilter::TYPE_AUDIO_BOOK,
                QueryFilter::TYPE_ALL
            ])]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
