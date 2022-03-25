<x-layouts.admin-layout>

    <x-slot name="title">Перечень баннеров</x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.banners.create')" label="Создать баннер"></x-href-add>
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
                            <x-th-sortable name="is_active">Активна?</x-th-sortable>
                            <th>Категория</th>
                            <x-th-sortable name="name">Название</x-th-sortable>
                            <th>Изображение</th>
                            <th>Текст</th>
                            <x-th-sortable name="link">Ссылка</x-th-sortable>
                            <th>Альтернативный контент</th>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>
                                    {{ $banner->id }}
                                </td>
                                <td>
                                    {{$banner->is_active ? 'Да' : 'Нет'}}
                                </td>
                                <td>
                                    @foreach($banner->genres??[] as $genres)
                                        <a href="{{route('admin.genres.edit', $genres)}}">{{$genres->name}}</a>
                                        @if(!$loop->last)
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    {!! $banner->name !!}
                                </td>
                                <td>
                                    {{$banner->image}}
                                </td>
                                <td>
                                    {{$banner->text}}
                                </td>
                                <td>
                                    {{$banner->link}}
                                </td>
                                <td>
                                    {{$banner->content}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.banners.edit', $banner)"></x-button-edit>

                                        <x-button-delete
                                            :route="route('admin.banners.destroy', $banner)"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$banners->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>

