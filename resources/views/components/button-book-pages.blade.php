<div class="pr-1 pb-1">
    <a
        class="btn btn-outline-info"
        href="{{route('admin.books.pages.index', $book)}}"
        title="Список страниц"
    >
        <i class="fa-solid fa-file-lines"></i>

        @if(!($short ?? true))
            Список страниц
        @endif
    </a>
</div>
