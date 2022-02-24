<?php

namespace App\View\Components;

use App\Models\BookGenre;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Genres extends Component
{
    public Collection $genres;
    public array $genreId;

    public function __construct(array $genreId = [])
    {
        $this->genres = BookGenre::select(['id', 'name'])->get();
        $this->genreId = $genreId;
    }

    public function render()
    {
        return view('components.genres');
    }
}

