@extends('layouts.admin_layout')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Список всех книг

        <x-slot name="actions">
            <a class="btn btn-success bg-gradient-success" href="{{route('admin.books.create')}}">
                <i class="fa fa-plus"></i> Добавить книгу
            </a>
        </x-slot>
    </x-header>
    <!-- /.content header -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Доступна?</th>
                            <th>Название</th>
                            <th>Жанры</th>
                            <th>Автор</th>
                            <th>Год</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td>
                                    {{ $book->id }}
                                </td>
                                <td>
                                    {{$book->active ? 'Да' : 'Нет'}}
                                </td>
                                <td>
                                    {!! $book->title !!}
                                </td>
                                <td>
                                    @foreach($book->genres??[] as $genre)
                                        {{$genre->name}} <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($book->authors??[] as $author)
                                        {{$author->author}} <br>
                                    @endforeach
                                </td>
                                <td>
                                    {{$book->year?->year}}
                                </td>
                                <td class="text-right">
                                    <x-button-edit :route="route('admin.book.edit', $book)"></x-button-edit>

                                    <x-button-delete :route="route('admin.books.destroy', $book)"></x-button-delete>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$books->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
