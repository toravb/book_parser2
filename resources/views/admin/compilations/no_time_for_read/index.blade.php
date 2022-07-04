<x-layouts.admin-layout>

    <x-slot name="title">Новинки книг(Главная страница)</x-slot>

{{--    <x-slot name="actions">--}}
{{--        <x-href-add :route="route('admin.books.create')" label="Добавить книгу"></x-href-add>--}}
{{--    </x-slot>--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
{{--                <div class="card-header">--}}
{{--                    <x-search placeholder="Поиск по ID и названию"></x-search>--}}
{{--                </div>--}}
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
                        @foreach($audiobooks[0]['audioBooks'] as $audiobook)
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
                                    @foreach($audiobook->genres??[] as $genre)
                                        <a href="{{route('admin.genres.edit', $genre)}}">{{$genre->name}}</a>
                                        @if(!$loop->last)
                                            <br>
                                        @endif
                                    @endforeach
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
{{--                                        <x-button-edit :route="route('admin.books.edit', $book)"></x-button-edit>--}}

{{--                                        <x-button-delete :route="route('admin.books.destroy', $book)"></x-button-delete>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

{{--                    {{$books->links()}}--}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
