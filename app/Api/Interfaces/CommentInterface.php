<?php

namespace App\Api\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface CommentInterface
{
    public function getComments(int $typeId, int $paginate);

    public function getCommentsOnComment(int $commentId, int $paginate);

    public function userLike(): HasOne;
}
