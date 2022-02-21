@extends('layouts.admin_layout')

@section('title', 'Редактировать книгу')

@section('content')
    <!-- Content header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать книгу: {{$book['title']}}</h1>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h4><i class="icon fa fa-check"></i>{{session('success')}}</h4>
                </div>

            @endif
        </div>
    </div>
    <!-- /.content header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <form action="{{route('admin.store.edit.books', $book['id'])}}" method="post">
                @csrf
                @method("PUT")
                <div class="card-body">
                    <div class="form-group">
                        <label for="bookTitleInput">Название книги</label>
                        <input type="text" value="{{$book->title}}" name="title" class="form-control" id="bookTitleInput">
                    </div>
                    <div class="form-group">
                        <label for="bookAuthorInput">Автор</label>
                        <input type="text" value="{{$book}}"  name="authorName" class="form-control" id="bookTitleInput" >
                    </div>
                    <div class="form-group">
                        <label for="bookTextInput">Описание</label>
                        <input type="text"  name="text" class="form-control" id="bookTextInput" placeholder="Enter text">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- select -->
                            <div class="form-group">
                                <label>Жанр</label>
                                <select class="form-control"  name="genre">
{{--                                    @foreach($categories as $category)--}}
{{--                                        <option>{{$category['name']}}</option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- select -->
                            <div class="form-group">
                                <label>Статус</label>
                                <select class="form-control"  name="status">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bookInputFile">File input</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-left: 50px">
                            <label for="bookYearInput">Год издания</label>
                            <input type="text"  name="year" class="form-control" id="bookTitleInput" placeholder="Enter year">
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </form>
        </div>

    </section>

@endsection
