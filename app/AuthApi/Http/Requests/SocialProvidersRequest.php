<?php

namespace App\AuthApi\Http\Requests;

use App\AuthApi\Services\SocialAuthService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialProvidersRequest extends FormRequest
{

    protected $redirect;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function __construct()
    {
        $this->redirect = url(config('app.front_url')) . '?error=This social network is unsupported!';
    }

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
            'provider' => ['required', Rule::in(SocialAuthService::getSocialProviders())]
        ];
    }

    public function validationData()
    {
        return $this->route()->parameters();
    }
}
