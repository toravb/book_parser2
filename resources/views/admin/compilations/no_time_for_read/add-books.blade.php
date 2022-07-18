<x-layouts.admin-layout>

    <x-slot name="title">Список всех книг</x-slot>
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
                            <th>Категории</th>
                            <th>Автор</th>
                            <x-th-sortable name="year">Год</x-th-sortable>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($audioBooks as $audiobook)
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
                                        {{$audiobook->genre->name}} <br>
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
                                        @if(!$audiobook->added)
                                            <x-button-add
                                                :route="route('admin.compilations.no-time-for-read.edit',$audiobook)">
                                            </x-button-add>
                                        @endif
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
