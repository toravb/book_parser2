<textarea
    @if($required??false)
    required
    @endif
    @if($uploadRoute??false)
        data-upload-route="{{$uploadRoute}}"
    @endif
    name="{{$name}}"
    rows="{{$rows??25}}"
    @class(['form-control rich-editor', 'is-invalid' => $errors->has($name)])
>{{$slot}}</textarea>
