<x-layouts.admin-layout>

    <x-slot name="title">Редактировать книгу "{{$book->title}}"</x-slot>

    <div class="content">
        <form
            action="{{route('admin.books.update', $book)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$book->id}}">

            <div class="card-body">
                <label class="col-12 d-block">
                    Название книги
                    <input
                        required
                        type="text"
                        name="title"
                        value="{{$book->title}}"
                        @class(['form-control', 'is-invalid' => $errors->has('title')])
                        placeholder="Название книги">

                    <x-error name="title"></x-error>
                </label>

                <label class="col-12 d-block">
                    Автор книги
                    <x-select2
                        required
                        multiple
                        :route="route('admin.authors.index')"
                        text-field="author"
                        name="authors_ids[]"
                    >
                        @foreach($book->authors as $author)
                        <option value="{{$author->id}}" selected>{{$author->author}}</option>
                        @endforeach
                    </x-select2>

                    <x-error name="authors_ids"></x-error>
                </label>

                <label class="col-12 d-block">
                    Год издания
                    <x-select2
                        required
                        :route="route('admin.years.index')"
                        text-field="year"
                        name="year_id"
                    >
                        <option value="{{$book->year?->id}}" selected>{{$book->year?->year}}</option>
                    </x-select2>

                    <x-error name="author_id"></x-error>
                </label>

                <label class="col-12 d-block">
                    Описание
                    <textarea
                        rows="5"
                        name="text"
                        @class(['form-control', 'is-invalid' => $errors->has('text')])
                    >{{$book->description}}</textarea>

                    <x-error name="description"></x-error>
                </label>

                <label class="col-12 d-block">
                    Категории книги
                    <x-genres-checkbox :selected-genres-id="$book->genres->pluck('id')->toArray()"></x-genres-checkbox>
                </label>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- status select -->
                        <div class="form-group">
                            <label>Статус активности</label>
                            <x-error name="status"></x-error>

                            <div class="form-check">
                                <label class="d-block col-12 form-check-label">
                                    <input
                                        type="radio"
                                        name="active"
                                        value="1"
                                        @if($book->active)
                                        checked
                                        @endif
                                        class="form-check-input">
                                    Активна
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="d-block col-12 form-check-label">
                                    <input
                                        type="radio"
                                        name="active"
                                        value="0"
                                        @if(!$book->active)
                                        checked
                                        @endif
                                        class="form-check-input">
                                    Скрыта
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
