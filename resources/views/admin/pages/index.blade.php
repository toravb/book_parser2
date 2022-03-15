<x-layouts.admin-layout>

    <x-slot name="title">
        Список страниц книги <x-book-link :book="$book"/>
    </x-slot>

    <x-slot name="actions">
        <x-href-add :route="route('admin.books.pages.create', $book)" label="Добавить страницу"></x-href-add>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <x-search placeholder="Поиск по ID, содержанию и порядковому номеру"></x-search>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <x-th-sortable name="id" style="width: 100px;">ID</x-th-sortable>
                            <x-th-sortable name="page_number" style="width: 280px;">Порядковый номер страницы</x-th-sortable>
                            <x-th-sortable name="content">Контент</x-th-sortable>
                            <th style="width: 100px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>
                                    {{ $page->id }}
                                </td>
                                <td>
                                    {{ $page->page_number }}
                                </td>
                                <td>
                                    {!! $page->content !!}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <x-button-edit :route="route('admin.books.pages.edit', [$book, $page])"></x-button-edit>

                                        <x-button-delete :route="route('admin.books.pages.destroy', [$book, $page])"></x-button-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$pages->links()}}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
