<?php

namespace App\Http\Requests;

use App\Interfaces\PolymorphTypes;
use App\Rules\IsExists;
use App\Rules\IsInFavorites;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateFavoriteRequest extends FormRequest
{
    public $types = [];
    public $morph = [];
    public $userId;

    public function __construct(PolymorphTypes $polymorphTypes)
    {
        $this->morph = $polymorphTypes->getTypes();
        $this->types = array_keys($this->morph);
        $this->userId = Auth::id();
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
        if (is_string($this->favorites_type)) {
            if (in_array($this->favorites_type, $this->types)) {
                return [
                    'favorites_id' => [
                        'bail',
                        'required',
                        'integer',
                        new IsInFavorites($this->favorites_type, $this->morph, $this->userId, $this->favorites_id),
                        new IsExists($this->favorites_type, $this->morph, $this->favorites_id)
                    ]
                ];
            } else {
                return [
                    'favorites_type' => ['bail', 'required', 'string', Rule::in($this->types)],
                ];
            }
        } else {
            return [
                'favorites_type' => ['bail', 'required', 'string'],
            ];
        }
    }
}
