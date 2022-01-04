<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class GetEventsRequest extends FormRequest
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
            'type' => 'sometimes|array',
            'type.*' => 'sometimes|integer',
            'category' => 'sometimes|array',
            'recent' => 'sometimes|in:true,false|exclude_if:week,true|exclude_if:byDate,true',
            'week' => 'sometimes|boolean|exclude_if:recent,true|exclude_if:byDate,true',
            'byDate' => 'sometimes|date_format:Y-m-d|exclude_if:week,true|exclude_if:recent,true',
            'search' => 'sometimes|string',
            'organizer_type' => 'sometimes|bail|string|in:organizer,artist',
            'location' => 'sometimes|required|array',
            'location.lat' => 'required_with:location|numeric|min:-90|max:90',
            'location.lng' => 'required_with:location|numeric|min:-180|max:180',
            'order' => 'sometimes|string|in:asc,desc'
        ];
    }
}
