<x-layouts.admin-layout>

    <x-slot name="title">Книги в подборке {{ $compilation->title }}</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.compilations.add-books', [$compilation])"
                    label="Добавить книги"></x-href-add>

        <x-href-add :route="route('admin.compilations.add-audiobooks', [$compilation])"
                    label="Добавить аудиокниги"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Обложка</th>
                            <th>Тип</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($compilation['books'] as $book)
                            <tr>
                                <td>
                                    {{ $book->id }}
                                </td>
                                <td>
                                    {!! $book->title !!}
                                </td>
                                <td>
                                    <a
                                        target="_blank"
                                        href="{{ Storage::url($book['image']?->public_path) }}"
                                    >
                                        <img
                                            src="{{ Storage::url($book['image']?->public_path) }}"
                                            class="img-thumbnail img-size-64"
                                            alt="Обложка"
                                        />
                                    </a>
                                </td>
                                <td>
                                    {{ $book->type == 'books' ? 'Книга' : 'Аудиокнига' }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-delete
                                            :text="('')"
                                            :title="('Удалить книгу из подборки?')"
                                            :route="route('admin.compilations.remove-book',[$compilation, $book->id, $book->type])"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach($compilation['audioBooks'] as $book)
                            <tr>
                                <td>
                                    {{ $book->id }}
                                </td>
                                <td>
                                    {!! $book->title !!}
                                </td>
                                <td>
                                    <a
                                        target="_blank"
                                        href="{{ Storage::url($book['image']?->public_path) }}"
                                    >
                                        <img
                                            src="{{ Storage::url($book['image']?->public_path) }}"
                                            class="img-thumbnail img-size-64"
                                            alt="Обложка"
                                        />
                                    </a>
                                </td>
                                <td>
                                    {{ $book->type == 'books' ? 'Книга' : 'Аудиокнига' }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-delete
                                            :text="('')"
                                            :title="('Удалить книгу из подборки?')"
                                            :route="route('admin.compilations.remove-book',[$compilation, $book->id, $book->type])"></x-button-delete>
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
