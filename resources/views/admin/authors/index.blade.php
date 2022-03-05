<x-layouts.admin-layout>

    <x-slot name="title">Список авторов</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.authors.create')" label="Добавить автора"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 100px;">ID</th>
                            <th style="width: 250px;">Изображение</th>
                            <th>Автор</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($authors as $author)
                            <tr>
                                <td>
                                    {{ $author->id }}
                                </td>
                                <td>
                                    {{$author->avatar}}
                                </td>
                                <td>
                                    {{$author->author}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.authors.edit', $author)"></x-button-edit>

                                        <x-button-delete :route="route('admin.authors.destroy', $author)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$authors->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
