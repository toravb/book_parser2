<x-layouts.admin-layout>

    <x-slot name="title">Редактировать год издания "{{$year->year}}"</x-slot>

    <!-- Main content -->
    <div class="content">
        <form
            action="{{route('admin.years.update', $year)}}"
            method="post"
            enctype="multipart/form-data"
            class="card"
        >

            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$year->id}}">

            <div class="card-body">
                <label class="col-12 d-block">
                    Год издания
                    <input
                        required
                        type="number"
                        step="1"
                        name="year"
                        value="{{$year->year}}"
                        @class(['form-control', 'is-invalid' => $errors->has('year')])
                    >

                    <x-error name="year"></x-error>
                </label>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>

</x-layouts.admin-layout>
