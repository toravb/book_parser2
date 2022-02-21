@extends('layouts.admin_layout')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Список всех книг

        <x-slot name="actions">
            <button type="button" class="btn btn-success bg-gradient-success">
                <i class="fa fa-plus"></i> Добавить книгу
            </button>
        </x-slot>
    </x-header>
    <!-- /.content header -->

    <div class="card-body">
        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>id</th>
                <th>Активность</th>
                <th>Название</th>
                <th>Category</th>
                <th>Автор</th>
                <th>Жанр</th>
                <th>Год</th>
            </tr>
            </thead>
            <tbody>
            @foreach($books as $book)

                <tr>
                    <td>
                        {{ $book['id'] }}
                    </td>
                    <td>
                        {{$book['active']}}
                    </td>
                    <td>

                        <a href="">{!! $book['title'] !!}</a>
                    </td>
                    <td>
                        @if($book->bookGenres ?? false)
                            @foreach($book->bookGenres as $bookGenre)
                                {{$bookGenre->name}} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($book->authors ?? false)
                            @foreach($book->authors as $bookAuthor)
                                {{$bookAuthor->author}} <br>

                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($book->bookGenres ?? false)
                            @foreach($book->bookGenres as $bookGenre)
                                {{$bookGenre->name}} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        {{$book->year->year}}
                    </td>
                    <td class="project-actions text-right">
                        <x-button-edit :route="route('admin.edit.books', $book)" label="Редактировать"></x-button-edit>

                        <a class="btn btn-danger btn-sm" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
