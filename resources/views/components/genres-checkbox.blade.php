@foreach($genres as $genre)
    <div class="form-check">
        <label class="form-check-label">
            <input
                type="checkbox"
                class="form-check-input"
                name="genres_id[]"
                value="{{$genre->id}}"
                @if(in_array($genre->id, $selectedGenresId))
                checked
                @endif
            >
            {{$genre->name}}
        </label>
    </div>
@endforeach
