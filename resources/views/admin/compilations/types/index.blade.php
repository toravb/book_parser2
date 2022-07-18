<x-layouts.admin-layout>

    <x-slot name="title">Типы подборок</x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form
                        action="{{route('admin.compilation-types.store')}}"
                        method="POST"
                        class="form-inline align-items-end"
                    >
                        @csrf

                        <label class="d-block col-12 col-sm-4 col-md-3 mr-2 mb-2">
                            Год издания
                            <input
                                type="text"
                                name="type"
                                placeholder="Введите тип"
                                required
                                @class(['form-control','w-100', 'is-invalid' => $errors->has('type')])
                            >

                            <x-error name="type"></x-error>
                        </label>

                        <div class="col">
                        <button type="submit" class="btn btn-success mb-2">
                            Добавить год издания
                        </button>
                        </div>
                    </form>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 100px;">ID</th>
                            <th>Тип подборки</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td>
                                    {{ $type->id }}
                                </td>
                                <td>
                                    {{ $type->year }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.compilation-types.edit', $type)"></x-button-edit>

                                        <x-button-delete :route="route('admin.compilation-types.destroy', $type)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$types->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
