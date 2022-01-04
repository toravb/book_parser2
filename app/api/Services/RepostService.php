<?php

namespace App\Services;

use App\Interfaces\PolymorphTypes;
use App\Post;

class RepostService
{

    private $types = [];
    private $repost = [
        'post',
        'repost_post',
        'repost_event',
        'repost_photo',
        'repost_video'
    ];

    public function __construct(PolymorphTypes $polymorphTypes)
    {
        $this->types = $polymorphTypes->getRepostTypes();
    }

    private function getPostedId($type, $id)
    {
        if (in_array($type, $this->repost)) {
            $posted = Post::find($id);

            return $posted->posted_id;
        }

        return $id;
    }

    private function defineType($type)
    {
        if ($this->isRepost($type)) {
            return 'repost_' . $type;
        }

        return $type;
    }

    public function createRepost($author, $id, $type)
    {
        $post = new Post();
        $post->user_id = $author;
        $post->type = $this->defineType($type);
        $post->posted_type = $this->types[$type];
        $post->posted_id = $this->getPostedId($type, $id);
        $post->save();

//       /* if ($this->isRepost($type)) {
//            StaticticsRepostedPhoto::insert([
//                'photo_id' => $id,
//                'user_id' => $author
//            ]);
//        }*/

        return $post;
    }

    public function isRepost($type)
    {
        return strpos($type, 'repost') === false;
    }
}
