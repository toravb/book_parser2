@extends('layouts.main')
@section('title', 'Pages')
@section('content')
    <div class="container">
            @if($book)
                <h2>{{$book->title}}</h2>
                @if($pages)
                    @foreach($pages as $page)
                    <div class="">
                        {!! $page->content !!}
                    </div>
                    @endforeach
                @endif
            @endif
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{$pages}}
                    </ul>
                </div>
    </div>
@endsection
