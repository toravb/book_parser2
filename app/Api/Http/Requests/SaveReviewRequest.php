<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SaveReviewRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getReviewModelTypes();
        $this->types = array_keys($types->getReviewTypes());

        parent::__construct();
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
            'id' => [
                'required',
                'integer',
                Rule::exists($this->models[$this->type] ?? null . '_id'),
                Rule::unique($this->type . '_reviews', $this->type . '_id')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    })
            ],
            'review_type' => ['required', 'integer', 'exists:review_types,id'],
            'title' => ['required', 'string', 'max:150'],
            'text' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
          'id.exists' => 'Книга не найдена',
          'id.unique' => 'Вы уже рецензировали данное произведение'
        ];
    }
}

