<?php

namespace App\Http\Requests;

use App\Interfaces\PolymorphTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateReportRequest extends FormRequest
{
    private $types = [];
    private $models = [];

    public function __construct(PolymorphTypes $polymorphTypes)
    {
        $this->models = $polymorphTypes->checkTypeIdForRepost();
        $this->types = array_keys($this->models);
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
            'id' => ['required', 'integer', 'exists:' . $this->models[$this->type] . ',id'],
            'type' => ['required', Rule::in($this->types)]
        ];
    }
}
