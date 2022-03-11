<?php

namespace App\Api\Http\Requests;

use App\AuthApi\Http\Requests\SocialProvidersRequest;
use App\AuthApi\Services\SocialAuthService;
use Illuminate\Validation\Rule;

class SocialProviderBindingRequest extends SocialProvidersRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'hash' => 'required|string|exists:App\AuthApi\Models\IdSocialNetwork,temp_token'
        ]);
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
