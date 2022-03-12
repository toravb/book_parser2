<select
    data-action="select2"
    @if(isset($route))
    data-ajax="{{$route}}"
    @endif
    data-key="{{$key??'id'}}"
    data-text-field="{{$textField}}"
    data-pagination="{{$pagination ?? true}}"
    name="{{$name}}"
    style="width: 100%;"
    @class(['form-control', 'is-invalid' => $errors->has($name)])
    {{$attributes}}
>
    {{$slot}}
</select>
