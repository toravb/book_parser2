@foreach($genres as $genre)
    <label class="col-12 font-weight-normal">
        <input type="checkbox" name="genre_id[]" value="{{$genre->id}}"
               @if(in_array($genre->id, $genreId)) checked @endif>
        <span>{{$genre->name}}</span>
    </label>
@endforeach
