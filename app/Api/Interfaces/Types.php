<?php

namespace App\Api\Interfaces;

interface Types
{
    public function getCommentTypes();

    public function getLikeTypes(): array;

    public function getBookTypes(): array;

    public function getReviewTypes(): array;
}
