<x-layouts.admin-layout>

    <x-slot name="title">Создание подборки</x-slot>

    <form
        action="{{route('admin.compilations.store')}}"
        method="post"
        enctype="multipart/form-data"
        class="card"
    >

        @csrf

        <div class="card-body">
            <label class="col-12 d-block">
                Название подборки
                <input
                    required
                    type="text"
                    name="title"
                    value="{{old('title')}}"
                    @class(['form-control', 'is-invalid' => $errors->has('title')])
                    placeholder="Название подборки"
                >

                <x-error name="title"></x-error>
            </label>

            <label class="col-12 d-block">
                Тип подборки
                <x-select2
                    required
                    :route="route('admin.compilation-types.index')"
                    text-field="name"
                    name="type_id"
                >
                </x-select2>

                <x-error name="type_id"></x-error>
            </label>

            <label class="col-12 d-block">
                Описание
                <textarea
                    required
                    rows="5"
                    name="description"
                        @class(['form-control', 'is-invalid' => $errors->has('description')])
                    >{{old('description')}}</textarea>

                <x-error name="description"></x-error>
            </label>

            <label class="col-12 d-block">
                Обложка подборки
                <input
                    required
                    type="file"
                    name="background"
                    class="form-control-file"
                >
                <x-error name="background"></x-error>
            </label>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Добавить</button>
            </div>
        </div>
    </form>

</x-layouts.admin-layout>
