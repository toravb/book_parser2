<x-layouts.admin-layout>

    <x-slot name="title">Список всех книг</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.books.create')" label="Добавить книгу"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form action="" method="GET" class="form-inline justify-content-end">
                        <input
                            type="text"
                            name="search"
                            value="{{request()->query('search') ?? old('search')}}"
                            class="form-control mb-1 mr-1"
                            placeholder="Поиск по ID и названию"
                        >

                        <button type="submit" class="btn btn-success mb-1 mr-1">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <x-th-sortable name="id">ID</x-th-sortable>
                            <th>Активна?</th>
                            <th>Название</th>
                            <th>Категории</th>
                            <th>Автор</th>
                            <th>Год</th>
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

                                        <x-button-delete :route="route('admin.books.destroy', $book)"></x-button-delete>
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
