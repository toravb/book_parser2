<form action="{{$route ?? null}}" method="GET" class="form-inline justify-content-end">
    <label>
        <input
            type="text"
            name="search"
            value="{{request()->query('search') ?? old('search')}}"
            class="form-control mb-1 mr-1"
            placeholder="{{$placeholder ?? 'Поиск'}}"
        >
    </label>

    <button type="submit" class="btn btn-success mb-1 mr-1">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</form>

