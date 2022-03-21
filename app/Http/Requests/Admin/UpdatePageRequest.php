<?php

namespace App\Http\Requests\Admin;

use App\Models\Page;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends StorePageRequest
{
    public function rules(): array
    {
        $rules = [
            'page_id' => ['required', 'int', Rule::exists(Page::class, 'id')],
            'page_number' => [
                'required',
                'int',
                'min:0',
                Rule::unique(Page::class)->where('book_id', $this->book_id)->ignore($this->page_id)
            ],
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
