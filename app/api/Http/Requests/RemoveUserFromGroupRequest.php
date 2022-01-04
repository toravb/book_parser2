<?php

namespace App\Http\Requests;

use App\Rules\IsNotOwnerWhenDeleteUser;
use App\Rules\IsOwnerOfGroup;
use Illuminate\Foundation\Http\FormRequest;

class RemoveUserFromGroupRequest extends FormRequest
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
            'group_id' => ['required', 'exists:groups,id'],
            'user_id' => [
                'required',
                'exists:users,id',
                new IsOwnerOfGroup($this->group_id),
                new IsNotOwnerWhenDeleteUser($this->group_id)
            ],
        ];
    }
}
