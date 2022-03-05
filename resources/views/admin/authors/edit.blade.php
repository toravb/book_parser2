<x-layouts.admin-layout>

    <x-slot name="title">Редактировать автора "{{$author->author}}"</x-slot>

    <div class="content">
        <form
            action="{{route('admin.authors.update', $author)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$author->id}}">

            <div class="card-body">
                <label class="col-12 d-block">
                    Автор
                    <input
                        required
                        type="text"
                        name="author"
                        value="{{$author->author}}"
                        @class(['form-control', 'is-invalid' => $errors->has('author')])
                        placeholder="Имя автора">

                    <x-error name="author"></x-error>
                </label>

                <label class="col-12">
                    Изображение автора

                    @if($author->avatar)
                        <div class="col-12 col-md-4">
                            <a href="{{Storage::url($author->avatar)}}" target="_blank">
                                <img
                                    src="{{Storage::url($author->avatar)}}"
                                    class="img-thumbnail mb-2"
                                    alt=""
                                >
                            </a>
                        </div>
                    @endif
                    <input
                        type="file"
                        name="avatar"
                        class="form-control-file">

                    <x-error name="avatar"></x-error>
                </label>

                @if($author->avatar)
                    <div class="form-check">
                        <label class="col-12 form-check-label">
                            <input
                                type="checkbox"
                                name="remove_avatar"
                                @class(['form-check-input'])
                            >
                            Удалить изображение?
                        </label>
                    </div>
                @endif

                <label class="col-12 d-block">
                    Описание
                    <textarea
                        rows="5"
                        name="about"
                            @class(['form-control', 'is-invalid' => $errors->has('about')])
                        >{{$author->about}}</textarea>

                    <x-error name="about"></x-error>
                </label>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
