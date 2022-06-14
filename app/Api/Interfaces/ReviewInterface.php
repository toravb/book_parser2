<?php


namespace App\Api\Interfaces;

interface ReviewInterface
{
    public function getReviews(int $id);

    public function getUserReviews(int $userID, $request);
}

