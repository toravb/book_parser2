<?php

namespace App\Http\Requests;

use App\Interfaces\PolymorphTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCommentRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    public function __construct(PolymorphTypes $types)
    {
        $this->models = $types->getTypes();
        $this->types = array_keys($types->getTypes());
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
        if (is_string($this->type) and $this->type !== '' and $this->type !== null) {
            if (in_array($this->type, $this->types)) {
                return [
                    'id' => ['required', 'integer', 'exists:' . $this->models[$this->type] . ',id'],
                    'comment_text' => ['required', 'string', 'max:1500']
                ];
            }

            return ['type' => [Rule::in($this->types)]];
        }

        return ['type' => ['required', 'string']];
    }
}
