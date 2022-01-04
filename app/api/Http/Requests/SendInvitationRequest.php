<?php

namespace App\Http\Requests;

use App\Rules\IsUserNotHaveInvitation;
use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
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
            'user_id' => ['required', 'unique:users_groups,user_id', new IsUserNotHaveInvitation()],
            'group_id' => ['required', 'exists:groups,id']
        ];
    }
}
