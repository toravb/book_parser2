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
