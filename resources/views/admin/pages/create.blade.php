<x-layouts.admin-layout>

    <x-slot name="title">Добавление страницы для книги <x-book-link :book="$book"/></x-slot>

    <form
        action="{{route('admin.books.pages.store', $book)}}"
        method="post"
        enctype="multipart/form-data"
        class="card"
    >

        @csrf
        <input type="hidden" name="book_id" value="{{$book->id}}">

        <div class="card-body">
            <label class="col-12 d-block">
                Содержание страницы
                <textarea
                    required
                    name="content"
                    rows="10"
                    @class(['form-control', 'is-invalid' => $errors->has('content')])
                >{{old('content')}}</textarea>

                <x-error name="content"></x-error>
            </label>

            <label class="col-12 d-block">
                Номер страницы
                <input
                    required
                    type="number"
                    min="0"
                    value="{{old('page_number')}}"
                    name="page_number"
                    @class(['form-control', 'is-invalid' => $errors->has('page_number')])
                >

                <x-error name="page_number"></x-error>
            </label>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Добавить</button>
        </div>
    </form>

</x-layouts.admin-layout>
