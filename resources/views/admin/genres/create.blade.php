<x-layouts.admin-layout>

    <x-slot name="title">Добавление категории</x-slot>

    <!-- /.content header -->
    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.genres.store')}}"
            method="post"
            enctype="multipart/form-data"
            class="card">

            @csrf

            <div class="card-body">
                <label class="col-12">
                    Название категории
                    <input
                        type="text"
                        name="name"
                        value="{{old('name') ?? null}}"
                        required
                        @class(['form-control', 'is-invalid' => $errors->has('name')])>

                    <x-error name="name"></x-error>
                </label>

                <label class="col-12">
                    Алиас
                    <input
                        type="text"
                        name="alias"
                        value="{{old('alias') ?? null}}"
                        {{--                        TODO if this field will not fill, it must fill from "name" field--}}
                        required
                        @class(['form-control', 'is-invalid' => $errors->has('alias')])>

                    <x-error name="alias"></x-error>
                </label>

                <label class="col-12">
                    Meta title
                    <input
                        type="text"
                        name="meta_title"
                        value="{{old('meta_title') ?? null}}"

                        @class(['form-control', 'is-invalid' => $errors->has('meta_title')])>

                    <x-error name="meta_title"></x-error>
                </label>

                <label class="col-12">
                    Meta description
                    <input
                        type="text"
                        name="meta_description"
                        value="{{old('meta_description') ?? null}}"

                        @class(['form-control', 'is-invalid' => $errors->has('meta_description')])>

                    <x-error name="meta_description"></x-error>
                </label>

                <label class="col-12">
                    Meta keyword
                    <input
                        type="text"
                        name="meta_keyword"
                        value="{{old('meta_keyword') ?? null}}"

                        @class(['form-control', 'is-invalid' => $errors->has('meta_keyword')])>

                    <x-error name="meta_keyword"></x-error>
                </label>

                <label class="col-12">
                    Description

                    <textarea
                    name="description"
                    @class(['form-control', 'is-invalid' => $errors->has('description')])>{{old('description') ?? null}}</textarea>

                    <x-error name="description"></x-error>
                </label>

                <div class="col-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input
                                type="checkbox"
                                name="is_hidden"
                                @if(old('is_hidden') ?? false)
                                checked
                                @endif
                                class="form-check-input">
                            Скрыта?

                            <x-error name="is_hidden"></x-error>
                        </label>
                    </div>
                </div>
            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    Добавить категорию
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin-layout>
