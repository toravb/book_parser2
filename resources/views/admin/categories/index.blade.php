@extends('layouts.admin_layout')

@section('title', 'Категории')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Список жанров

        <x-slot name="actions">
            <a class="btn btn-success bg-gradient-success" href="{{route('admin.categories.create')}}">
                <i class="fa fa-plus"></i> Добавить жанр
            </a>
        </x-slot>
    </x-header>
    <!-- /.content header -->

    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Название</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    {{$category->id}}
                                </td>
                                <td>
                                    {{$category->name}}
                                </td>
                                <td class="text-right">
                                    <x-button-edit
                                        :route="route('admin.categories.edit', $category)">
                                    </x-button-edit>

                                    <x-button-delete
                                        :route="route('admin.categories.destroy', $category->id)">
                                    </x-button-delete>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </section>

@endsection
