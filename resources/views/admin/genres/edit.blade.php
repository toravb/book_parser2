<x-layouts.admin-layout>
    <!-- Content header (Page header) -->
    <x-slot name="title">
        Редактирование категории "{{$genre->name}}"
    </x-slot>

    <!-- /.content header -->
    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.genres.update', $genre)}}"
            method="post"
            enctype="multipart/form-data"
            class="card">

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$genre->id}}">

            <div class="card-body">
                <label class="col-12">
                    Название категории
                    <input
                        type="text"
                        name="name"
                        value="{{$genre->name}}"
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
                                @if($genre->is_hidden)
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
                    Сохранить категорию
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
