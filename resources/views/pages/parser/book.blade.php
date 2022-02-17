@extends('layouts.main')
@section('title', 'Pages')
@section('content')
    <div class="container container-table">
        <div class="sub-container">
            <h2>Книги</h2>
            <ul>
                @if($books)
                    @foreach($books as $book)
                        <li><a href="{{route('admin.books.item', ['id' => $book->id])}}">{{$book->title}}</a></li>
                    @endforeach
                @endif
            </ul>
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{$books}}
                </ul>
            </div>
        </div>
    </div>
@endsection
