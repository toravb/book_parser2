<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'link_source' => ['required', 'string'],
            'link_content' => ['required', 'string'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'agreement' => ['required', 'boolean'],
            'copyright_holder' => ['required', 'boolean'],
            'interaction' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
