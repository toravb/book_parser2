<?php

namespace App\Api\Filters;

use App\Models\Quote;

class QuoteFilter extends QueryFilter
{
    public function groupBy(string $groupBy)
    {
        if ($groupBy === Quote::SORT_BY_BOOK_TITLE) {
            return $this->builder
                ->join('books', 'book_id', '=', 'books.id')
                ->orderBy('title', 'asc');
        }

        if ($groupBy === Quote::SORT_BY_AUTHOR) {
            return $this->builder
                ->join('books', 'quotes.book_id', '=', 'books.id')
                ->join('author_to_books', 'author_to_books.book_id', '=', 'books.id')
                ->join('authors', 'authors.id','=', 'author_to_books.author_id')
                ->orderBy('author', 'asc');
        }

        return $this->builder;
    }
}
