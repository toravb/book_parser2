<?php

namespace App\Api\Http\Requests;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Controllers\BookController;
use App\Models\Book;
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
        return [
            'showType'=>['required', Rule::in([QueryFilter::SHOW_TYPE_BLOCK, QueryFilter::SHOW_TYPE_LIST])],
            'selectionCategory'=>['sometimes','nullable', 'integer', 'exists:compilations,type'],
            'bookType'=>['sometimes', 'string', 'exists:book_compilation,compilationable_type'],
        ];
    }
}
