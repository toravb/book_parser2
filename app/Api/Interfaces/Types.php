<?php

namespace App\Api\Interfaces;

interface Types
{
    public function getCommentTypes(): array;

    public function getLikeTypes(): array;

    public function getBookTypes(): array;

    public function getNotificationTypes(): array;

    public function getReviewTypes(): array;

    public function getRecommendTypes(): array;


}
