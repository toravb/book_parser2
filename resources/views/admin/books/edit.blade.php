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
                        :route="route('admin.authors.index')"
                        text-field="author"
                        name="author_id"
                    >
                        <option value="{{$book->author?->id}}" selected>{{$book->author?->author}}</option>
                    </x-select2>

                    <x-error name="author_id"></x-error>
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
                    >{{$book->text}}</textarea>

                    <x-error name="description"></x-error>
                </label>

                <label class="col-12 d-block">
                    Категории книги
                    <x-genres-checkbox :selected-genres-id="$book->genres->pluck('id')->toArray()"></x-genres-checkbox>
                </label>

                <div class="form-group col-12 col-md-6">
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

                <hr>

                <p><b>SEO настройки</b></p>

                <label class="col-12 d-block">
                    Meta-description
                    <input
                        type="text"
                        name="meta_description"
                        value="{{$book->meta_description}}"
                        @class(['form-control', 'is-invalid' => $errors->has('meta_description')])
                    >

                    <x-error name="meta_description"></x-error>
                </label>

                <label class="col-12 d-block">
                    Meta-keywords
                    <input
                        type="text"
                        name="meta_keywords"
                        value="{{$book->meta_keywords}}"
                        @class(['form-control', 'is-invalid' => $errors->has('meta_keywords')])
                    >

                    <x-error name="meta_keywords"></x-error>
                </label>

                <label class="col-12 d-block">
                    Alias
                    <input
                        type="text"
                        name="alias_url"
                        value="{{$book->alias_url}}"
                        @class(['form-control', 'is-invalid' => $errors->has('alias_url')])
                    >

                    <x-error name="alias_url"></x-error>
                </label>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
