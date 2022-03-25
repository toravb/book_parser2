<?php

namespace App\Http\Requests\Admin;

use App\Models\Banner;
use Illuminate\Validation\Rule;

class UpdateBannerRequest extends StoreBannerRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'integer', Rule::exists(Banner::class, 'id')]
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
