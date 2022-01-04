<?php

namespace App\Http\Requests;

class UnsubcribeRequest extends SubscribeRequest
{


    public function validationData()
    {
        return $this->route()->parameters();
    }

    public function messages()
    {
        return [
            'user_id.not_in' => 'You can not unfollow yourself',
        ];
    }
}
