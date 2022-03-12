@extends('layouts.admin_layout')

@section('title', 'Добавление жанра')

@section('content')
    <x-header>
        Добавление жанра
    </x-header>

    <div class="content">
        <form
            action="{{route('admin.categories.store')}}"
            method="post"
            enctype="multipart/form-data"
            class="card">

            @csrf

            <div class="card-body">
                <div class="row mb-3 pb-3 border-bottom">
                    <label class="col-12 d-block">
                        Название жанра
                        <input
                            required
                            type="text"
                            name="name"
                            @class(['form-control', 'is-invalid' => $errors->has('name')])
                            placeholder="Название жанра">

                        <x-error name="name"></x-error>
                    </label>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
