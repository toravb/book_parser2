<?php

namespace App\Api\Interfaces;

interface CommentInterface
{
    public function getComments(int $typeId, int $paginate);

    public function getCommentsOnComment(int $commentId, int $paginate);
}
