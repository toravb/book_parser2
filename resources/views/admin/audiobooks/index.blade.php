<x-layouts.admin-layout>

    <x-slot name="title">Список всех аудио книг</x-slot>

    <x-slot name="actions">
        <a class="btn btn-success bg-gradient-success" href="{{route('admin.audio-books.create')}}">
            <i class="fa fa-plus"></i> Добавить книгу
        </a>
    </x-slot>

    <!-- /.content header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <x-search placeholder="Поиск по ID и названию"></x-search>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <x-th-sortable name="id" style="width: 100px;">ID</x-th-sortable>
                            <x-th-sortable name="active" style="width: 125px;">Активна?</x-th-sortable>
                            <th style="width: 250px">Обложка</th>
                            <x-th-sortable name="title">Название</x-th-sortable>
                            <th>Жанр</th>
                            <th>Автор</th>
                            <x-th-sortable name="year" style="width: 100px;">Год</x-th-sortable>
                            <th style="width: 100px;"></th>
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
                                    @if($audioBook->image)
                                        <a href="{{Storage::url($audioBook->image->link)}}" target="_blank">
                                            <img src="{{Storage::url($audioBook->image->link)}}" alt=""
                                                 class="img-thumbnail">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {!!$audioBook->title!!}
                                </td>
                                <td>
                                    {{$audioBook->genre?->name}}
                                </td>
                                <td>
                                    @foreach($audioBook->authors??[] as $author)
                                        {{$author->author}} <br>
                                    @endforeach
                                </td>
                                <td>
                                    {{$audioBook->year?->year}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit
                                            :route="route('admin.audio-books.edit', $audioBook)"></x-button-edit>

                                        <x-button-delete
                                            :route="route('admin.audio-books.destroy', $audioBook )"></x-button-delete>
                                    </div>
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
</x-layouts.admin-layout>
