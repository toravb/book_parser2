@extends('layouts.main')
@section('title', 'Parser')
@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Параметры</th>
                    <th>Жанр</th>
                    <th>Серия</th>
                    <th>Авторы</th>
                    <th>Актёры озвучки</th>
                    <th>Litres</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>

                        </td>
                        <td>
                            <a href="{{route('audio.books.show', ['book' => $book])}}">{!!$book->title!!}</a>
                        </td>
                        <td>
                            {!!$book->description!!}...
                        </td>
                        <td>
                            @php
                                $params = json_decode($book->params, true);
                            @endphp
                            @foreach($params as $param => $values)
                                {{$param}} :<br>
                                @foreach($values as $value)
                                    <span style="padding-left: 10px">
                                    {{$value}}<br>
                                    </span>
                                @endforeach
                            @endforeach
                        </td>
                        <td>
                            <a href="{{route('audio.books.genre', ['genre' => $book->genre])}}">{{$book->genre->name}}</a>
                        </td>
                        <td>
                            @if($book->series)
                                <a href="{{route('audio.books.series', ['series' => $book->series])}}">{{$book->series->name}}</a>
                            @endif
                        </td>
                        <td>
                        @foreach($book->authors as $author)
                            <a href="{{route('audio.books.author', ['author' => $author])}}">{!!$author->name!!}</a>
                            <hr>
                        @endforeach
                        </td>
                        <td>
                            @foreach($book->actors as $actor)
                                <a href="{{route('audio.books.actor', ['actor' => $actor])}}">{!!$actor->name!!}</a>
                                <hr>
                            @endforeach
                        </td>
                        <td>
                            {{$book->litres}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{$books}}
            </ul>
        </div>
    </div>
@endsection
