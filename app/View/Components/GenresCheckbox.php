<?php

namespace App\View\Components;

use App\Models\Genre;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class GenresCheckbox extends Component
{
    public Collection $genres;
    public array $selectedGenresId;

    public function __construct(array $selectedGenresId = [])
    {
        $this->genres = Genre::select(['id', 'name'])->get();
        $this->selectedGenresId = $selectedGenresId;
    }

    public function render()
    {
        return view('components.genres-checkbox');
    }
}

