<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Api\Http\Controllers\SearchController;

class SearchRequest extends FormRequest
{
    public array $types;
    public function __construct(Types $types)
    {
        $paginationableTypes = array_keys($types->getSearchableTypes());
        $this->types = $paginationableTypes;
        $this->types[] = SearchController::TYPE_SHORT_PAGE;
        $this->types[] = SearchController::TYPE_FULL_PAGE;
    }

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
            'type' => ['required', 'string', Rule::in($this->types)],
            'search' => ['required', 'string']
        ];
    }

    public function messages()
    {
       return [
           'search.required' => ['Текст поиска обязателен']
       ];
    }
}
