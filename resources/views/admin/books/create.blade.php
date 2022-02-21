@extends('layouts.admin_layout')

@section('title', 'Добавить книгу')

@section('content')
    <!-- Content header (Page header) -->
    <x-header>
        Добавление книги
    </x-header>
    <!-- /.content header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <form action="{{route('admin.book.store')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="bookTitleInput">Название книги</label>
                        <input type="text" name="title" class="form-control" id="bookTitleInput"
                               placeholder="Название книги">
                    </div>
                    <div class="form-group">
                        <label for="bookTextInput">Описание</label>
                        <input type="text" name="text" class="form-control" id="bookTextInput" placeholder="Краткое описание книги">
                    </div>
                    <div class="form-group">
                        <label for="bookAuthorInput">Автор</label>
                        <input type="text" name="authorName" class="form-control" id="bookTitleInput"
                               placeholder="Автор книги">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="bookYearInput">Год издания</label>
                            <input type="text" name="year" class="form-control" id="bookYearInput"
                                   placeholder="Год издания книги">
                        </div>
                        <div class="col-sm-6">
                            <!-- select -->
                            <div class="form-group">
                                <label>Статус</label>
                                <select class="form-control" name="status">
                                    <option id="1">В общем доступе</option>
                                    <option id="0">Скрыта</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="bookInputFile">File input</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="bookCoverImage">Обложка</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="coverImage">
                                    <label class="custom-file-label" for="exampleInputFile">Выберите файл изображения обложки книги</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </form>
        </div>

    </section>

@endsection
