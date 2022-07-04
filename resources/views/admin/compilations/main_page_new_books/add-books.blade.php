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
                                <td>
                                    <div class="d-flex">
                                        @if(!$book->added)
                                        <x-button-add
                                            :route="route('admin.compilations.novelties.add-to-novelties',$book)">
                                        </x-button-add>
                                        @endif
                                    </div>
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
</x-layouts.admin-layout>
