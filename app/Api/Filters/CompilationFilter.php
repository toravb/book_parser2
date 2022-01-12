<?php

namespace App\Api\Filters;

use Illuminate\Http\Request;

class CompilationFilter extends QueryFilter
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function showType(string $showType)
    {
        if ($showType === 'list') {

            return $this->builder->with([
                'books' => function ($query) {
                    return $query->with(['authors', 'image'])
                        ->select('id', 'title')
                        ->withCount('rates')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                },
                'audioBooks' => function ($query) {
                    return $query
//                        ->with(['authors', 'image'])
                        ->select('id', 'title');
//                        ->withCount('rates')
//                        ->withAvg('rates as rates_avg', 'rates.rating');
                }

            ])
                ->select(['id', 'title']);

        }


        if ($showType === 'block') {
            return $this->builder
                ->select(['id', 'title', 'background'])
                ->withCount('books');

        }
    }


    public function selectionCategory(string $selectionCategory){

        return $this->builder->where('type', $selectionCategory);
    }

    public function bookType(string $bookType){

        return $this->builder->whereHas($bookType);

//        if($bookType === 'book'){
//            return $this->builder->whereHas('books');
//        }
//        if($bookType === 'audioBook'){
//            return $this->builder->whereHas('audioBooks');
//        }
    }
}
