<x-layouts.admin-layout>

    <x-slot name="title">Редактировать баннер "{{$banner->name}}"</x-slot>

    <div class="content">
        <form
            action="{{route('admin.banners.update', $banner)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$banner->id}}">

            <div class="card-body">
                <label class="col-12 d-block">
                    Название баннера
                    <input
                        required
                        type="text"
                        name="name"
                        value="{{$banner->name}}"
                        @class(['form-control', 'is-invalid' => $errors->has('name')])
                        placeholder="Название баннера">

                    <x-error name="name"></x-error>
                </label>

                <label class="col-12 d-block">
                    Ссылка
                    <input
                        required
                        type="text"
                        name="link"
                        value="{{$banner->link}}"
                        @class(['form-control', 'is-invalid' => $errors->has('link')])
                        placeholder="Ссылка">

                    <x-error name="link"></x-error>
                </label>

                <label class="col-12 d-block">
                    Текст баннера
                    <input
                        type="text"
                        name="text"
                        value="{{$banner->text}}"
                        @class(['form-control', 'is-invalid' => $errors->has('text')])
                        placeholder="Текст баннера">

                    <x-error name="text"></x-error>
                </label>


                <label class="col-12 d-block">
                    Изображение для баннера

                    <input
                        type="file"
                        name="image"
                        class="form-control-file"
                    >

                    <x-error name="image"></x-error>
                </label>

                @if($banner->image)
                    <div class="form-group">
                        <div class="col-12 col-sm-6 col-md-4 mb-2">
                            <a
                                target="_blank"
                                href="{{Storage::url($banner->image)}}"
                            >
                                <img
                                    src="{{Storage::url($banner->image)}}"
                                    alt=""
                                    class="img-thumbnail"
                                >
                            </a>
                        </div>

                        <div class="form-check">
                            <label class="col-12 form-check-label">
                                <input
                                    type="checkbox"
                                    name="image_remove"
                                    value="1"
                                    class="form-check-input"
                                >
                                Удалить изображение?

                                <x-error name="image_remove"></x-error>
                            </label>
                        </div>
                    </div>
                @endif

                <label class="col-12 d-block">
                    Альтернативный контент
                    <textarea
                        rows="5"
                        name="alt_content"
                        @class(['form-control', 'is-invalid' => $errors->has('alt_content')])
                        >{{$banner->content}}</textarea>

                    <x-error name="alt_content"></x-error>
                </label>

                <label class="col-12 d-block">
                    Привязка к категориям:
                    <x-genres-checkbox
                        :selected-genres-id="$banner->genres->pluck('id')->toArray()"></x-genres-checkbox>
                </label>

                <div class="form-group col-12 col-md-6">
                    <label>Статус активности</label>
                    <x-error name="status"></x-error>

                    <div class="form-check">
                        <label class="d-block col-12 form-check-label">
                            <input
                                type="radio"
                                name="is_active"
                                value="1"
                                @if($banner->is_active)
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
                                name="is_active"
                                value="0"
                                @if(!$banner->is_active)
                                checked
                                @endif
                                class="form-check-input">
                            Скрыта
                        </label>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
