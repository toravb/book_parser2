<?php

namespace App\Http\Requests;

use App\Interfaces\Types;
use App\Rules\CheckAccountType;
use App\Rules\CheckUserType;
use App\Rules\ExistImagesBackground;
use App\Rules\ExistMediaUploaded;
use App\Rules\ExistMediaSelected;
use App\Rules\IsArtistTakePartInHisEvent;
use App\Traits\EventTypesTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateEventRequest extends FormRequest
{
    use EventTypesTrait;

    public $types = [];
    public $mediaTypes;
    public $user;

    public function __construct(Types $mediaTypes)
    {
        $this->types = $this->eventTypes();
        $this->mediaTypes = $mediaTypes;
        $this->user = Auth::user();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array("createEvent", $this->user->accountType->permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'type_id' => ['required', new CheckAccountType($this->types, 'id'),],
            'category_id' => ['nullable', new CheckUserType($this->types, $this->type_id, 'id')],
            'title' => ['required', 'string', 'max:50'],
            'begin_at' => ['required', 'date_format:Y-m-d H:i'],
            'end_at' => ['required', 'date_format:Y-m-d H:i', 'after:begin_at'],
            'place' => ['required', 'string', 'max:100'],
            'address' => [ 'required_if:status,public', 'nullable', 'string', 'max:255'],
            'eventbrite_link' => ['nullable', 'string', 'max:255', 'regex:/^https:\/\/www.eventbrite.co\D+e\/.+$/'],
            'event_link' => ['nullable', 'string', 'max:255', 'url'],
            'free_event' => ['required', 'boolean'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:50000'],
            'artists' => ['bail', 'nullable', 'array', new IsArtistTakePartInHisEvent($this->user)],
            'artists.*' => ['nullable', 'array'],
            'artists.*.amazonStorage' => ['required', 'boolean'],
            'artists.*.id' => ['required'],
            'artists.*.name' => ['required', 'string', 'max:100'],
            'artists.*.photo' => ['nullable', 'string', 'max:70'],
            'frequency' => ['required', 'string', 'max:100'],
            'tags' => ['nullable', 'array'],
            'status' => ['required', 'string', 'max:20'],
            'background' => ['required', new ExistImagesBackground()],
            'media_uploaded' => ['array', new ExistMediaUploaded()],
            'media_uploaded.*' => ['nullable', 'string'],
            'media_selected.*' => ['nullable', 'integer'],
            'media_selected' => ['nullable', 'array', new ExistMediaSelected('photo', $this->mediaTypes)],
            'video_selected' => ['nullable', 'array', new ExistMediaSelected('video', $this->mediaTypes)],
            'location' => ['nullable', 'array'],
            'location.lat' => ['required_with:address', 'numeric', 'min:-90', 'max:90'],
            'location.lng' => ['required_with:address', 'numeric', 'min:-180', 'max:180']

        ];
    }

    public function messages()
    {
        return [
            'address.required_if' => 'Address required',
            'location.lat.required_with' => 'Enter an existing address',
            'location.lng.required_with' => 'Enter an existing address',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'begin_at' => date_format(date_create($this->begin_at), 'Y-m-d H:i'),
            'end_at' => date_format(date_create($this->end_at), 'Y-m-d H:i'),
        ]);
    }
}
