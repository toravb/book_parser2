<x-layouts.admin-layout>
    <!-- Content header (Page header) -->
    <x-slot name="title">
        Редактирование типа рецензии "{{$reviewType->type}}"
    </x-slot>

    <!-- /.content header -->
    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.review-types.update', $reviewType)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$reviewType->id}}">

            <div class="card-body">
                <label class="col-12">
                    Тип рецензии
                    <input
                        required
                        type="text"
                        name="type"
                        value="{{$reviewType->type}}"
                        @class(['form-control', 'is-invalid' => $errors->has('type')])>

                    <x-error name="type"></x-error>
                </label>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    Сохранить категорию
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
