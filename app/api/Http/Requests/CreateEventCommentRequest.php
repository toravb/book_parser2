<?php

namespace App\Http\Requests;

class CreateEventCommentRequest extends CreateEventLikeRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'comment_text' => ['required', 'max:1500', 'string']
        ]);
    }
}
