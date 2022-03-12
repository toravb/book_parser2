<a class="btn btn-success" href="{{$route}}">
    <i class="fa-solid fa-plus"></i>
    @if(isset($label))
        {{$label}}
    @else
        Добавить запись
    @endif
</a>
