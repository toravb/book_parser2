@extends('layouts.main')
@section('title', 'Pages')
@section('content')

    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Серия</th>
                    <th>Жанр</th>
                    <th>Авторы</th>
                    <th>Издатели</th>
                    <th>Год</th>
                    <th>Превью</th>
                    <th>Параметры</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <?php
                    $img = explode('/', $book->image->link);
                    $path = '';
                    for ($i = 3; $i < count($img); $i++){
                        $path .= '/'.$img[$i] ;
                    }
                    ?>

                    <tr>

                        <td><img src="{{$path}}" alt=""></td>
                        <td><a href="{{route('books.item', ['id' => $book->id])}}">{{$book->title}}</a></td>
                        <td>
                            @if($book->series)
                                {{$book->series->series}}
                            @endif
                        </td>
                        <td>
                            @if($book->genre)
                                {{$book->genre->name}}
                            @endif
                        </td>
                        <td>
                            @if($book->authors)
                                @for($i = 0; $i < count($book->authors); $i++)
                                    @if($i < count($book->authors)-1)
                                    {{$book->authors[$i]->author}},
                                    @else
                                        {{$book->authors[$i]->author}}
                                    @endif
                                @endfor
                            @endif
                        </td>
                        <td>
                            @if($book->publishers)
                                @for($i = 0; $i < count($book->publishers); $i++)
                                    @if($i < count($book->publishers)-1)
                                        {{$book->publishers[$i]->publisher}},
                                    @else
                                        {{$book->publishers[$i]->publisher}}
                                    @endif
                                @endfor
                            @endif
                        </td>
                        <td>
                            @if($book->year)
                                {{$book->year->year}}
                            @endif
                        </td>
                        <td>
                            {{$book->text}}
                        </td>
                        <td>
                            <?php
                            $params = json_decode($book->params, true);
                            ?>
                            @if($params)
                                @foreach($params as $key => $value)
                                    {{$key}} {{$value}}<br>
                                @endforeach
                            @endif
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
