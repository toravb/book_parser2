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
        if (!array_key_exists($type, $bookTypes)) {
            throw new \Exception('Book type do not exists');
        }
        return new $bookTypes[$type];
    }
}
