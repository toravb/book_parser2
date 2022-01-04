<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditEventRequest extends CreateEventRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'id' => ['required', 'numeric']
        ]);
    }
}
