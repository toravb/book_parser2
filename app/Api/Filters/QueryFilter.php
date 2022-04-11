<?php

namespace App\Api\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    const PER_PAGE_BLOCKS = 40;
    const PER_PAGE_LIST = 13;
    const PER_PAGE_LIST_AUDIO = 14;
    const SHOW_TYPE_BLOCK = 'block';
    const SHOW_TYPE_LIST = 'list';
    const SORT_BY_DATE = '1';
    const SORT_BY_READERS_COUNT = '2';
    const SORT_BY_LISTENERS = '2';
    const SORT_BY_RATING_LAST_YEAR = '3';
    const SORT_BY_REVIEWS = '4';
    const BESTSELLERS = '5';
    const TYPE_BOOK = 'books';
    const TYPE_AUDIO_BOOK = 'audioBooks';
    const TYPE_ALL = 'all';
    const SORT_BY_ALPHABET = '6';
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = $field;
            if (is_callable([$this, $method]) and method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        return array_filter(
            array_map('trim', $this->request->all())
        );
    }
}
