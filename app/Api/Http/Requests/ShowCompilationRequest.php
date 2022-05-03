<?php

namespace App\Api\Http\Requests;

use App\Api\Filters\QueryFilter;
use App\Models\Compilation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowCompilationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->selectionCategory === Compilation::CATEGORY_ALL) {
            return [
                'showType' => ['required', Rule::in([QueryFilter::SHOW_TYPE_BLOCK, QueryFilter::SHOW_TYPE_LIST])],
                'bookType' => ['required', 'string', Rule::in(Compilation::$availableCompilationableTypes)],
                'selectionCategory' => ['required', 'integer']
            ];
        }
        return [
            'showType' => ['required', Rule::in([QueryFilter::SHOW_TYPE_BLOCK, QueryFilter::SHOW_TYPE_LIST])],
            'selectionCategory' => ['required', 'integer', 'exists:compilations,type'],
            'bookType' => ['required', 'string', Rule::in(Compilation::$availableCompilationableTypes)],
        ];

    }
}
