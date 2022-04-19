<x-layouts.admin-layout>
    <x-slot name="title">
        Список типов рецензий
    </x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.review-types.create')" label="Добавить тип"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <x-th-sortable name="id" style="width: 150px;">ID</x-th-sortable>
                            <x-th-sortable name="type">тип</x-th-sortable>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reviewTypes as $reviewType)
                            <tr>
                                <td>
                                    {{$reviewType->id}}
                                </td>
                                <td>
                                    {{$reviewType->type}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.review-types.edit', $reviewType)"></x-button-edit>

                                        <x-button-delete :route="route('admin.review-types.destroy', $reviewType)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

{{--                    {{$reviewType->links()}}--}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
