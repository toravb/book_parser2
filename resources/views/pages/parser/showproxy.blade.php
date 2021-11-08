@extends('layouts.main')
@section('title', 'Pages')
@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>id</th>
                    <th>proxy</th>
                    <th>blocked</th>
                    <th>update_time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->proxy}}</td>
                        <td>{{$item->blocked}}</td>
                        <td>{{$item->update_time}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{$items}}
            </ul>
        </div>
    </div>







@endsection
