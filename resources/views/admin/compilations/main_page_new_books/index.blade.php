<x-layouts.admin-layout>

    <x-slot name="title">Новинки книг(Главная страница)</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.compilations.novelties.books-for-novelties')" label="Добавить книгу"></x-href-add>
    </x-slot>

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
                                        <a href="{{route('admin.genres.edit', $genre)}}">{{$genre->name}}</a>
                                        @if(!$loop->last)
                                            <br>
                                        @endif
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
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.books.edit', $book)"></x-button-edit>

                                        <x-button-remove :route="route('admin.compilations.novelties.remove-from-novelties', $book['id'])"></x-button-remove>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
