@extends('layouts.admin_layout')

@section('content')
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
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
                        <a href="">{!!$book['active']!!}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
