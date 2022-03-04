@extends('layouts.admin_layout')

@section('title', 'Редактирование категории')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Редактирование категории
    </x-header>
    <x-error-alerts/>
    <!-- /.content header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{route('admin.category.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-1 p-0">
                                <label for="genreInput">ID</label>
                                <input type="text" name="id" class="form-control" id="genreInput"
                                       value="{{$category['id']}}" readonly>
                            </div>
                            <div class="col-4 p-0">
                                <label for="genreInput">Жанр</label>
                                <input type="text" name="genre" class="form-control" id="genreInput"
                                       value="{{$category['name']}}">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </section>

@endsection
