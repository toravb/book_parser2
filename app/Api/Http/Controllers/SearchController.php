<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\SearchRequest;
use App\Api\Interfaces\SearchRepositoryInterface;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use Elasticsearch\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    const TYPE_SHORT_PAGE = 'short';
    const TYPE_FULL_PAGE = 'full';

    public function search(SearchRepositoryInterface $searchRepository, SearchRequest $request, Types $typesGenerator, Client $elasticsearch)
    {
        $perPage = 8;
        $limitForPaginate = $perPage + 1;
        $offset = $request->page ? $request->page * $perPage : 0;
        $currentPage = $request->page ? $request->page : 0;
        $limitForPaginateArray = [];
        if ($request->type === self::TYPE_SHORT_PAGE) {
            $types = ['books', 'audio_books', 'authors'];
            $limitForPaginateArray = ['books' => 6, 'audio_books' => 6, 'authors' => 10];
        }
        if ($request->type === self::TYPE_FULL_PAGE) {
            $types = array_keys($typesGenerator->getSearchableTypes());
        }
        if ($request->type !== self::TYPE_FULL_PAGE && $request->type !== self::TYPE_SHORT_PAGE) {
            $types[] = $request->type;
        }

        $repositories = $typesGenerator->getSearchableRepositories();
        $response = [];
        foreach ($types as $type) {
            $repository = new $repositories[$type]( $elasticsearch,  $typesGenerator);
            $limit = array_key_exists($type, $limitForPaginateArray) ? $limitForPaginateArray[$type] : $limitForPaginate;
            $models = $repository->search($request->search, $limit, $offset, $type);

//            $pagination = new LengthAwarePaginator(
//                $models->slice($currentPage, $perPage),
//                $models->count(),
//                $perPage,
//                $currentPage,
//                [
//                    'path' => request()->url(),
//                    'query' => request()->query(),
//                ]
//            );

            $response[$type] = $models;
        }


        return ApiAnswerService::successfulAnswerWithData($response);
    }
}
