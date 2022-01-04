<?php

namespace App\Http\Requests;

use App\Interfaces\StatusesForAdminPanel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplaintsChangeStatusRequest extends FormRequest
{

    private $statuses = [];

    public function __construct(StatusesForAdminPanel $statusesForAdminPanel)
    {
        $this->statuses = $statusesForAdminPanel->getComplaintsStatusesForValidation();
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
        return [
            'id' => ['required', 'integer'],
            'status' => ['required', 'string', Rule::in($this->statuses)]
        ];
    }
}
