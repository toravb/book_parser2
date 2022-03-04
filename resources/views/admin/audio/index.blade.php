@extends('layouts.admin_layout')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Список всех книг

        <x-slot name="actions">
            <button class="btn btn-success bg-gradient-success" href="{{route('admin.book.create')}}">
                <i class="fa fa-plus"></i> Добавить книгу
            </button>
        </x-slot>
    </x-header>
    <!-- /.content header -->

    <div class="card-body">
        <table class="table table-bordered table table-striped projects">
            <thead>
            <tr>
                <th>id</th>
                <th>Активность</th>
                <th>Обложка</th>
                <th>Название</th>
                <th>Category</th>
                <th>Автор</th>
                <th>Год</th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
            @foreach($audioBooks as $audioBook)
                <tr>
                    <td>
                        {{ $audioBook['id'] }}
                    </td>
                    <td>
                        {{$audioBook['active']}}
                    </td>
                    <td>
                        @if($audioBook->image ?? false)
                            {{$audioBook->image->link}}
                        @endif
                    </td>
                    <td>
                        {{$audioBook['title']}}
                    </td>
                    <td>
                        @if($audioBook->genre ?? false)
                            @foreach($audioBook->genre as $genres)
                                {{$genres->name}}
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($audioBook->authors ?? false)
                            @foreach($audioBook->authors as $bookAuthor)
                                {{$bookAuthor->author}} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        {{$audioBook->year->year}}
                    </td>
                    <td class="project-actions text-right">
                        <x-button-edit :route="route('admin.book.edit', $audioBook)" label="Редактировать"></x-button-edit>
                        <a class="btn btn-danger btn-sm" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Удалить
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
