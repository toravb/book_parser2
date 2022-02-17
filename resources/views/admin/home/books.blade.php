@extends('layouts.admin_layout')

@section('content')
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>id</th>
                <th>Жанр</th>
                <th>Автор</th>
            </tr>
            </thead>
            <tbody>
            @foreach($book as $books)

                <tr>
                    <td>
                        <a href="{{route('admin.list.books', ['books' => $books])}}">{!!$books->id!!}</a>
                    </td>
                    <td>
                        <a href="{{route('admin.list.books', ['books' => $books])}}">{!!$books->title!!}</a>
                    </td>
                    <td>
                        <a href="{{route('admin.list.books', ['books' => $books])}}">{!!$books->active!!}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
