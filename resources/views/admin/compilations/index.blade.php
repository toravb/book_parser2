<x-layouts.admin-layout>

    <x-slot name="title">Список всех подборок</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.compilations.create')" label="Создать подборку"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <x-search placeholder="Поиск по ID и названию"></x-search>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <x-th-sortable name="id">ID</x-th-sortable>
                            <x-th-sortable name="title">Название</x-th-sortable>
                            <th>Обложка</th>
                            <th>Тип</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($compilations as $compilation)
                            <tr>
                                <td>
                                    {{ $compilation->id }}
                                </td>
                                <td>
                                    {!! $compilation->title !!}
                                </td>
                                <td>
                                    <a
                                        target="_blank"
                                        href="{{ Storage::url($compilation->background) }}"
                                    >
                                        <img
                                            src="{{ Storage::url($compilation->background) }}"
                                            class="img-thumbnail img-size-64"
                                            alt="Обложка"
                                        />
                                    </a>
                                </td>
                                <td>
                                    {{ $compilation['compilationType']->name }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit
                                            :route="route('admin.compilations.edit', $compilation)"></x-button-edit>

                                        <x-button-delete
                                            :route="route('admin.compilations.destroy', $compilation)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
