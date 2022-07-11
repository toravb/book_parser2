<x-layouts.admin-layout>

    <x-slot name="title">Редактировать подборку "{{$compilation->title}}"</x-slot>

    <div class="content">
        <form
            action="{{route('admin.compilations.update', $compilation)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$compilation->id}}">

            <div class="card-body">
                <label class="col-12 d-block">
                    Название подборки
                    <input
                        required
                        type="text"
                        name="title"
                        value="{{$compilation->title}}"
                        @class(['form-control', 'is-invalid' => $errors->has('title')])
                        placeholder="Название подборки">

                    <x-error name="title"></x-error>
                </label>

                <label class="col-12 d-block">
                    Описание
                    <textarea
                        rows="5"
                        name="description"
                        @class(['form-control', 'is-invalid' => $errors->has('description')])
                    >{{$compilation->description}}</textarea>

                    <x-error name="description"></x-error>
                </label>

                <label class="col-12 d-block">
                    Обложка

                    <input
                        type="file"
                        name="background"
                        class="form-control-file"
                    >

                    <x-error name="background"></x-error>
                </label>

                @if($compilation->background)
                    <div class="form-group">
                        <div class="col-12 col-sm-6 col-md-4 mb-2">
                            <a
                                target="_blank"
                                href="{{Storage::url($compilation->background)}}"
                            >
                                <img
                                    src="{{Storage::url($compilation->background)}}"
                                    alt="Обложка"
                                    class="img-thumbnail"
                                >
                            </a>
                        </div>

                        <div class="form-check">
                            <label class="col-12 form-check-label">
                                <input
                                    type="checkbox"
                                    name="background_image_remove"
                                    value="1"
                                    class="form-check-input"
                                >
                                Удалить обложку?

                                <x-error name="background_image_remove"></x-error>
                            </label>
                        </div>
                    </div>
                @endif

                <label class="col-12 d-block">
                    Тип подборки
                    <x-select2
                        required
                        :route="route('admin.compilation-types.index')"
                        text-field="name"
                        name="type_id"
                    >
                        <option value="{{$compilation->compilationType?->id}}"
                                selected>{{$compilation->compilationType?->name}}</option>
                    </x-select2>

                    <x-error name="type_id"></x-error>
                </label>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
