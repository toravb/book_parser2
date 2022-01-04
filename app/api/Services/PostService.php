<?php

namespace App\Services;

use App\Helpers\IsNotEmpty;

class PostService
{

    public function transformPostForView($post)
    {
        $post->liked = IsNotEmpty::isNotEmpty($post->likes);
        $post->favorited = IsNotEmpty::isNotEmpty($post->favorite);

        unset($post->likes);
        unset($post->favorite);

        if ($post->posted === null) {
            return $post;
        }

        if ($post->posted->media === null) {
            $photos = $post->posted->photos;


            $videos = $post->posted->videos;

            if ($photos === null) {
                $photos = [];
            }
            if ($videos === null) {
                $videos = [];
            } else {
                $videos = $videos->toArray();
            }

            $post->posted->media = array_merge($photos, $videos);


            unset($post->posted->videos);
            unset($post->posted->photos);
        }

        return $post;
    }
}
