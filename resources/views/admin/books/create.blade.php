@extends('layouts.admin_layout')

@section('title', 'Добавить книгу')

@section('content')
    <!-- Content header (Page header) -->
    <x-header>
        Добавление книги
    </x-header>

    <!-- /.content header -->
    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.books.store')}}"
            method="post"
            enctype="multipart/form-data"
            class="card">

            @csrf

            <div class="card-body">
                <div class="row mb-3 pb-3 border-bottom">
                    <label class="col-12 d-block">
                        Название книги
                        <input
                            required
                            type="text"
                            name="title"
                            @class(['form-control', 'is-invalid' => $errors->has('title')])
                            placeholder="Название книги">

                        <x-error name="title"></x-error>
                    </label>

                    <label class="col-12 d-block">
                        Описание
                        <textarea
                            required
                            rows="5"
                            name="description"
                            @class(['form-control', 'is-invalid' => $errors->has('title')])
                            placeholder="Краткое описание книги"></textarea>

                        <x-error name="description"></x-error>
                    </label>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- status select -->
                        <div class="form-group">
                            <label>Статус активности</label>
                            <x-error name="status"></x-error>

                            <div class="form-check">
                                <label class="d-block col-12 form-check-label">
                                    <input type="radio" name="status" value="1" class="form-check-input">
                                    Активна
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="d-block col-12 form-check-label">
                                    <input type="radio" name="status" value="0" checked class="form-check-input">
                                    Скрыта
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="w-100">
                                Файл книги
                                <input
                                    required
                                    type="file"
                                    name="book-file"
                                    class="form-control-file">

                                <x-error name="book-file"></x-error>
                            </label>

                        </div>

                        <div class="form-group mb-0">
                            <label class="w-100">
                                Обложка
                                <input
                                    required
                                    type="file"
                                    name="cover-image"
                                    class="form-control-file">

                                <x-error name="book-file"></x-error>
                            </label>
                        </div>
                    </div>

                    <!--genres checkbox-->
                    <div class="col-12 col-md-6">
                        <x-genres/>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>

@endsection
