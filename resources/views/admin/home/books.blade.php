@extends('layouts.admin_layout')

@section('content')
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>Category</th>
                <th>Active</th>
            </tr>
            </thead>
            <tbody>
            @foreach($books as $book)

                <tr>
                    <td>
                        {{ $book['id'] }}
                    </td>
                    <td>

                        <a href="">{!! $book['title'] !!}</a>
                    </td>
                    <td>
                        @if($book->bookGenres ?? false)
                            @foreach($book->bookGenres as $bookGenre)
                                {{$bookGenre->name}} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a href="">{!!$book['active']!!}</a>
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="{{route('admin.edit.books', $book['id'])}}">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
