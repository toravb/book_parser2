<?php

namespace App\Api\Interfaces;

interface CommentInterface
{
    public function getComments(int $bookId, int $paginate);

    public function getCommentsOnComment(int $commentId, int $paginate);
}
