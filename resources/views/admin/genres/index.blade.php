<x-layouts.admin-layout>
    <x-slot name="title">
        Список категорий
    </x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.genres.create')" label="Добавить категорию"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 150px;">ID</th>
                            <th style="width: 70px;">Скрыта?</th>
                            <th>Название</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($genres as $genre)
                            <tr>
                                <td>
                                    {{$genre->id}}
                                </td>
                                <td>
                                    {{$genre->is_hidden ? 'Да' : 'Нет'}}
                                </td>
                                <td>
                                    {{$genre->name}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.genres.edit', $genre)"></x-button-edit>

                                        <x-button-delete :route="route('admin.genres.destroy', $genre)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$genres->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
