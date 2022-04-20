<x-layouts.admin-layout>

    <x-slot name="title">Создание баннера</x-slot>

    <form
        action="{{route('admin.banners.store')}}"
        method="post"
        enctype="multipart/form-data"
        class="card"
    >

        @csrf

        <div class="card-body">
            <label class="col-12 d-block">
                Название баннера
                <input
                    required
                    type="text"
                    name="name"
                    value="{{old('name')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('name')])
                    placeholder="Название баннера"
                >

                <x-error name="name"></x-error>
            </label>

            <label class="col-12 d-block">
                Баннерная ссылка
                <input
                    required
                    type="text"
                    name="link"
                    value="{{old('link')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('link')])
                    placeholder="Баннерная ссылка"
                >

                <x-error name="link"></x-error>
            </label>

            <label class="col-12 d-block">
                Текст баннера
                <input
                    type="text"
                    name="text"
                    value="{{old('text')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('text')])
                    placeholder="Текст баннера"
                >

                <x-error name="text"></x-error>
            </label>

            <label class="col-12 d-block">
                Альтернативный контент
                <textarea
                    rows="5"
                    name="alt_content"
                        @class(['form-control', 'is-invalid' => $errors->has('alt_content')])
                    >{{old('alt_content')}}</textarea>

                <x-error name="content"></x-error>
            </label>

            <label class="col-12 d-block">
                Изображение

                <input
                    type="file"
                    name="image"
                    class="form-control-file"
                >

                <x-error name="image"></x-error>
            </label>

            <label class="col-12 d-block">
                Категории для размещения
                <x-genres-checkbox></x-genres-checkbox>
            </label>

            <div class="form-group col-12 col-md-6">
                <label>Статус активности</label>
                <x-error name="is_active"></x-error>

                <div class="form-check">
                    <label class="d-block col-12 form-check-label">
                        <input type="radio" name="is_active" value="1" class="form-check-input">
                        Активен
                    </label>
                </div>

                <div class="form-check">
                    <label class="d-block col-12 form-check-label">
                        <input type="radio" name="is_active" value="0" checked class="form-check-input">
                        Скрыт
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Добавить</button>
        </div>
    </form>

</x-layouts.admin-layout>
