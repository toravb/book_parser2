@extends('layouts.main')
@section('title', 'Parser')
@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Имя</th>
                </tr>
                </thead>
                <tbody>
                @foreach($actors as $actor)

                    <tr>
                        <td>
                            <a href="{{route('audio.books.actor', ['actor' => $actor])}}">{!!$actor->name!!}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{$actors}}
            </ul>
        </div>
    </div>
@endsection
