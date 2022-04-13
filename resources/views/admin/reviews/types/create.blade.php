<x-layouts.admin-layout>

    <x-slot name="title">Добавление типа рецензии</x-slot>

    <!-- /.content header -->
    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.review-types.store')}}"
            method="post"
            enctype="multipart/form-data"
            class="card">

            @csrf

            <div class="card-body">
                <label class="col-12">
                    Тип рецензии
                    <input
                        type="text"
                        name="type"
                        value="{{old('type') ?? null}}"
                        required
                        @class(['form-control', 'is-invalid' => $errors->has('type')])>

                    <x-error name="type"></x-error>
                </label>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        Добавить тип рецензии
                    </button>
                </div>
            </div>
</x-layouts.admin-layout>
