@extends('layouts.admin_layout')

@section('title', 'Добавить книгу')

@section('content')
    <!-- Content header (Page header) -->
    <x-header>
        Добавление книги
    </x-header>
    <x-error-alerts/>
    <!-- /.content header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <form action="{{route('admin.book.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="bookTitleInput">Название книги</label>
                        <input type="text" name="title" class="form-control" id="bookTitleInput"
                               placeholder="Название книги">
                    </div>
                    <div class="form-group">
                        <label for="bookTextInput">Описание</label>
                        <textarea name="description" class="form-control" id="bookDescription"
                                  placeholder="Краткое описание книги"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- select -->
                            <div class="form-group">
                                <label>Статус</label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="status"
                                           value="1">
                                    <label for="customRadio1" class="custom-control-label">Активна</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="status"
                                           value="0" checked="">
                                    <label for="customRadio2" class="custom-control-label">Скрыта</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="bookInputFile">Файл книги</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="book-file" class="file-input" id="book-file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="bookCoverImage">Обложка</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="cover-image" class="file-input" id="cover-image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <!-- /.card -->
            </form>
        </div>
    </section>

@endsection
