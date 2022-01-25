<?php

namespace App\Api\Factories;

use App\Api\Interfaces\BookInterface;
use App\Api\Interfaces\Types;

class BookFactory
{
    public function __construct(Types $types)
    {
        $this->types = $types;
    }

    public function createInstance(string $type): BookInterface
    {
        $bookTypes = $this->types->getBookTypes();
        return new $bookTypes[$type];
    }
}
