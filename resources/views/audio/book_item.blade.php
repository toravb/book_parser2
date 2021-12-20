@extends('layouts.main')
@section('title', 'Parser')
@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <h2>{!! $book->title !!}</h2>
                        </td>
                    </tr>
                    @if($book->image_name)
                    <tr>
                        <td>
                            <img src="{{ asset('audiobooks/'.$book->slug.'/'.$book->image_name) }}" alt="{{$book->title}}">
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td><b>Жанр</b></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{route('audio.books.genre', ['genre' => $book->genre])}}">{{$book->genre->name}}</a>
                        </td>
                    </tr>
                    @if($book->series)
                        <tr>
                            <td><b>Серия</b></td>
                        </tr>
                        <tr>
                            <td>

                                <a href="{{route('audio.books.series', ['series' => $book->series])}}">{{$book->series->name}}</a>

                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td><b>Авторы</b></td>
                    </tr>
                        @foreach($book->authors as $author)
                            <tr>
                        <td>
                            <a href="{{route('audio.books.author', ['author' => $author])}}">{!!$author->name!!}</a>
                        </td>
                            </tr>
                        @endforeach
                    <tr>
                        <td><b>Актёры озвучки</b></td>
                    </tr>
                        @foreach($book->actors as $actor)
                            <tr>
                            <td>
                                <a href="{{route('audio.books.actor', ['actor' => $actor])}}">{!!$actor->name!!}</a>
                            </td>
                            </tr>
                        @endforeach
                    <tr>
                        <td><b>Описание</b></td>
                    </tr>
                    <tr>
                        <td>{!! $book->description !!}</td>
                    </tr>
                    @if(!empty(json_decode($book->params)))
                    <tr>
                        <td><b>Параметры</b></td>
                    </tr>
                    <tr>
                        <td>
                            @foreach(json_decode($book->params, true) as $param => $values)
                                {{$param}} :<br>
                                @foreach($values as $value)
                                    <span style="padding-left: 10px">
                                    {{$value}}<br>
                                    </span>
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td><b>Litres</b></td>
                    </tr>
                    <tr>
                    <td>
                        {{$book->litres}}
                    </td>
                    </tr>
                    <tr>
                        <td><b>Аудио</b></td>
                    </tr>
                    @foreach($book->audiobooks->sortBy('index') as $audio)
                    <tr>
                            <td>
                                {{preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}, $audio->title)}}
                            </td>
                    </tr>
                    <tr>
                    <td>
                        {{$audio->link}}
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
