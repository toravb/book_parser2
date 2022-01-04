<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMessagesRequest extends FormRequest
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
            'chat_id' => ['required', 'integer', 'exists:chats,id'],
            'limit' => ['nullable', 'integer', 'max:10'],
            'skip' => ['nullable', 'integer'],
        ];
    }
}
