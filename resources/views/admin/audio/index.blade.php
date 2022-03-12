@extends('layouts.admin_layout')

@section('content')

    <!-- Content header (Page header) -->
    <x-header>
        Список всех книг

        <x-slot name="actions">
            <a class="btn btn-success bg-gradient-success" href="{{route('admin.audio_book.create')}}">
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
                            <th>id</th>
                            <th>Активна?</th>
                            <th>Обложка</th>
                            <th>Название</th>
                            <th>Жанр</th>
                            <th>Автор</th>
                            <th>Год</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($audioBooks as $audioBook)
                            <tr>
                                <td>
                                    {{ $audioBook->id}}
                                </td>
                                <td>
                                    {{$audioBook->active ? 'Да' : 'Нет'}}
                                </td>
                                <td>
                                    {{$audioBook->image?->link}}
                                </td>
                                <td>
                                    {!!$audioBook->title!!}
                                </td>
                                <td>
                                    @foreach($audioBook->genre??[] as $genres)
                                        {{$genres->name}}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($audioBook->authors??[] as $bookAuthor)
                                        {{$bookAuthor->author}} <br>
                                    @endforeach
                                </td>
                                <td>
                                    {{$audioBook->year?->year}}
                                </td>
                                <td class="text-right">
                                    <x-button-edit :route="route('admin.audio_book.edit', $audioBook)"></x-button-edit>

                                    <x-button-delete :route="route('admin.audio_book.destroy', $audioBook )"></x-button-delete>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$audioBooks->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
