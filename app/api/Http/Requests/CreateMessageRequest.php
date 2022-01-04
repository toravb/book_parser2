<?php

namespace App\Http\Requests;

use App\Rules\ExistingChatId;
use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
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
            'chat_id' => ['bail', 'present', new ExistingChatId($this->to)],
            'to' => ['required', 'integer', 'exists:users,id'],
            'text' => ['required_without:attachments', 'string', 'max:1000'],
            'attachments.type' => ['required_with:attachments', 'string', 'max:255'],
            'attachments.path' => ['nullable', 'string', 'max:100'],
            'attachments.status' => ['required_with:attachments', 'string', 'max:255'],
            'attachments.source' => ['required_with:attachments', 'string', 'in:medialibrary,uploaded'],
        ];
    }
}
