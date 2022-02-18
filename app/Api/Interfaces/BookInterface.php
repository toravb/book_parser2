<?php

namespace App\Api\Interfaces;

interface BookInterface {
    public function getBook();

    public function getBookForLetterFilter();

    public function  noveltiesBooks();
}
