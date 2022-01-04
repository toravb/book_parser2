<?php

namespace App\Http\Requests;

use App\Interfaces\Types;
use App\Services\StatisticsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatisticsUnLoggedViewRequest extends FormRequest
{

    public $types = [];
    public $models = [];


    public function __construct(StatisticsService $statisticsService, Types $types)
    {
        $this->models = $types->getStatisticsTypes();
        $this->types = array_keys($this->models);
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

        if (is_string($this->type)) {
            if (in_array($this->type, $this->types)) {
                if ($this->type === 'general') {
                    return [
                        'front_id' => ['required'],
                    ];
                }
                return [
                    'id' => ['bail', 'required', 'integer', 'exists:' . $this->models[$this->type]],
                    'front_id' => ['required'],
                    'place' => ['bail', 'required_if:type,event', 'string', 'in:page,card']
                ];
            } else {
                return [
                    'type' => ['required', 'string', Rule::in($this->types)]
                ];
            }
        } else {
            return [
                'type' => ['required', 'string']
            ];
        }
    }
}
