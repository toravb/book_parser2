<?php

namespace App\Api\Services;

use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Compilation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CompilationService extends Compilation
{
    public function showCompilationDetails($id)
    {
        return BookCompilation::with(['bookCompilationable' => function (MorphTo $morphTo) {
            $morphTo->constrain([
                Book::class => function (Builder $query) {
                    $query->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                },
                AudioBook::class => function (Builder $query) {
                    $query->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                },
            ])->morphWith([
                Book::class => ['authors', 'image', 'bookGenres:id,name'],
                AudioBook::class => ['authors', 'images', 'genre:id,name'],
            ]);
        }])->where(
            'compilation_id', $id
        )->simplePaginate(Compilation::COMPILATION_PER_PAGE);
    }
}
