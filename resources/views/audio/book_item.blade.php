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
                                <div class="" style="display: grid;
/*grid-template-columns: repeat(2, 1fr);*/
grid-template-rows: 0fr;
grid-column-gap: 0px;
grid-row-gap: 0px;">
                                    <div class="" style="grid-area: 1 / 1 / 2 / 2;">
                                        {{$audio->title}}
                                    </div>
                                    <div class="" style="grid-area: 1 / 2 / 2 / 3;">
                                       @if(Storage::disk('sftp')->exists($book->slug.'/'.Str::slug($audio->title).'.'.$audio->extension??'mp3'))
                                            <figure>
                                                {{--                                <figcaption>Listen to the T-Rex:</figcaption>--}}
                                                <audio
                                                    controls
                                                    src="{{asset('audiobooks/'.$book->slug.'/'.Str::slug($audio->title).'.'.$audio->extension)}}">
                                                    Your browser does not support the
                                                    <code>audio</code> element.
                                                </audio>
                                            </figure>
                                        @else
                                            {{$audio->link}}
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
