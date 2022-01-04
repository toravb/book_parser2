<?php

namespace App\Http\Requests;

use App\Rules\ChangeUserType;
use App\Rules\CheckUserType;
use Illuminate\Support\Facades\Auth;

class ProfileSaveRequest extends UserRequest
{
    public $user;
    public $accountType;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
        $this->accountType = $this->user->account_type;
    }

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
        return array_merge(parent::rules(), [
            'zip_code' => ['nullable', 'string', 'regex:/^(\d{5}|\d{5} \d{4}|\d{5}-\d{4})$/i'],
            'website' => ['nullable', 'string', 'max:50'],
            'twitter' => ['nullable', 'string', 'max:50'],
            'instagram' => ['nullable', 'string', 'max:60'],
            'facebook' => ['nullable', 'string', 'max:60'],
            'photo' => ['nullable', 'string', 'max:60', 'exists:media_photos,path'],
            'background' => ['nullable', 'string', 'max:60', 'exists:media_photos,path'],
            'country' => ['nullable', 'string', 'max:35'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'biography' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:50'],
            'user_type' => [
                'nullable',
                new CheckUserType($this->types, $this->accountType, 'name'),
                new ChangeUserType($this->user)
            ],
            'address_coordinates' => ['nullable', 'array', 'size:2'],
            'address_coordinates.lat' => ['required_with:address_coordinates,address', 'numeric', 'min:-90', 'max:90'],
            'address_coordinates.lng' => ['required_with:address_coordinates,address', 'numeric', 'min:-180', 'max:180']
        ]);
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'zip_code.regex' => 'The entered zip code does not exist'
        ]);
    }
}
