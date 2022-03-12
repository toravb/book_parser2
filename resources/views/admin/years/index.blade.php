<x-layouts.admin-layout>

    <x-slot name="title">Список годов издания</x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form
                        action="{{route('admin.years.store')}}"
                        method="POST"
                        class="form-inline align-items-end"
                    >
                        @csrf

                        <label class="d-block col-12 col-sm-4 col-md-3 mr-2 mb-2">
                            Год издания
                            <input
                                type="number"
                                step="1"
                                name="year"
                                placeholder="Введите год"
                                required
                                @class(['form-control','w-100', 'is-invalid' => $errors->has('year')])
                            >

                            <x-error name="year"></x-error>
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
                            <th>Год</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($years as $year)
                            <tr>
                                <td>
                                    {{ $year->id }}
                                </td>
                                <td>
                                    {{ $year->year }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.years.edit', $year)"></x-button-edit>

                                        <x-button-delete :route="route('admin.years.destroy', $year)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$years->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
