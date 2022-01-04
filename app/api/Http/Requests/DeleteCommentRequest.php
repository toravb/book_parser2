<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class DeleteCommentRequest extends CreateCommentRequest
{
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
                    'id' => ['required', 'integer'],
                ];
            }

            return ['type' => [Rule::in($this->types)]];
        }

        return ['type' => ['required', 'string']];
    }

    public function validationData()
    {
        return $this->route()->parameters();
    }
}
