<x-layouts.admin-layout>

    <x-slot name="title">Некогда читать - слушайте!(Главная страница)</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.compilations.no-time-for-read.add.audiobooks')"
                    label="Добавить книги"></x-href-add>
    </x-slot>

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
                            <x-th-sortable name="id">ID</x-th-sortable>
                            <x-th-sortable name="active">Активна?</x-th-sortable>
                            <x-th-sortable name="title">Название</x-th-sortable>
                            <th>Жанр</th>
                            <th>Автор</th>
                            <x-th-sortable name="year">Год</x-th-sortable>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($audiobooks as $audiobook)
                            <tr>
                                <td>
                                    {{ $audiobook->id }}
                                </td>
                                <td>
                                    {{$audiobook->active ? 'Да' : 'Нет'}}
                                </td>
                                <td>
                                    {!! $audiobook->title !!}
                                </td>
                                <td>
                                    {{ $audiobook->genre?->name }}
                                </td>
                                <td>
                                    @foreach($audiobook->authors??[] as $author)
                                        {{$author->author}} <br>
                                    @endforeach
                                </td>
                                <td>
                                    {{$audiobook->year?->year}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit
                                            :route="route('admin.audio-books.edit', $audiobook)"></x-button-edit>
                                        <x-button-delete
                                            :title="('Удалить книгу из подборки?')"
                                            :text="('')"
                                            :route="route('admin.compilations.no-time-for-read.destroy', $audiobook)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$audiobooks->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
