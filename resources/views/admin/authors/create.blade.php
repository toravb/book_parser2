<x-layouts.admin-layout>

    <x-slot name="title">Добавление автора</x-slot>

    <div class="content">
        <form
            action="{{route('admin.authors.store')}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >
            <div class="card-body">
                @csrf
                <label class="col-12 d-block">
                    Автор
                    <input
                        required
                        type="text"
                        name="author"
                        value="{{old('author')}}"
                        @class(['form-control', 'is-invalid' => $errors->has('author')])
                        placeholder="Имя автора">

                    <x-error name="author"></x-error>
                </label>

                <label class="col-12">
                    Изображение автора
                    <input
                        type="file"
                        name="avatar"
                        class="form-control-file">

                    <x-error name="avatar"></x-error>
                </label>

                <label class="col-12 d-block">
                    Описание
                    <textarea
                        rows="5"
                        name="about"
                            @class(['form-control', 'is-invalid' => $errors->has('about')])
                        >{{old('about')}}</textarea>

                    <x-error name="about"></x-error>
                </label>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Добавить</button>
            </div>
        </form>
    </div>
</x-layouts.admin-layout>
