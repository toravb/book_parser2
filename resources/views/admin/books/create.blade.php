<x-layouts.admin-layout>

    <x-slot name="title">Добавление книги</x-slot>

    <form
        action="{{route('admin.books.store')}}"
        method="post"
        enctype="multipart/form-data"
        class="card"
    >

        @csrf

        <div class="card-body">
            <label class="col-12 d-block">
                Название книги
                <input
                    required
                    type="text"
                    name="title"
                    value="{{old('title')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('title')])
                    placeholder="Название книги"
                >

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
                ></x-select2>

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
                </x-select2>

                <x-error name="author_id"></x-error>
            </label>

            <label class="col-12 d-block">
                Описание
                <textarea
                    rows="5"
                    name="text"
                        @class(['form-control', 'is-invalid' => $errors->has('text')])
                    >{{old('text')}}</textarea>

                <x-error name="text"></x-error>
            </label>

            <label class="col-12 d-block">
                Обложка книги

                <input
                    type="file"
                    name="cover_image"
                    class="form-control-file"
                >

                <x-error name="cover_image"></x-error>
            </label>

            <label class="col-12 d-block">
                Жанры книги
                <x-genres-checkbox></x-genres-checkbox>
            </label>

            <div class="form-group col-12 col-md-6">
                <label>Статус активности</label>
                <x-error name="active"></x-error>

                <div class="form-check">
                    <label class="d-block col-12 form-check-label">
                        <input type="radio" name="active" value="1" class="form-check-input">
                        Активна
                    </label>
                </div>

                <div class="form-check">
                    <label class="d-block col-12 form-check-label">
                        <input type="radio" name="active" value="0" checked class="form-check-input">
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
                    value="{{old('meta_description')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('meta_description')])
                >

                <x-error name="meta_description"></x-error>
            </label>

            <label class="col-12 d-block">
                Meta-keywords
                <input
                    type="text"
                    name="meta_keywords"
                    value="{{old('meta_keywords')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('meta_keywords')])
                >

                <x-error name="meta_keywords"></x-error>
            </label>

            <label class="col-12 d-block">
                Alias
                <input
                    type="text"
                    name="alias_url"
                    value="{{old('alias_url')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('alias_url')])
                >

                <x-error name="alias_url"></x-error>
            </label>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Добавить</button>
        </div>
    </form>

</x-layouts.admin-layout>
