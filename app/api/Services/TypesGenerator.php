<?php

namespace App\Services;

use App\Interfaces\Types;

class TypesGenerator implements Types
{
    protected $commentTypes = [
        'post' => 'App\\PostsComment',
        'event' => 'App\\EventComment',
        'photo' => 'App\\MediaPhotoComment',
        'video' => 'App\\VideoComment',
    ];

    protected $postTypes = [
        'post',
        'repost_post',
        'repost_event',
        'repost_photo'
    ];

    protected $mediaTypes = [
        'photo' => 'App\\MediaPhoto',
        'video' => 'App\\Video',
    ];

    protected $likeTypes = [
        'post' => 'App\\PostsLike',
        'event' => 'App\\EventLike',
        'photo' => 'App\\MediaPhotoLike',
        'video' => 'App\\VideoLike',
        'repost_post' => 'App\\PostsLike',
        'repost_event' => 'App\\PostsLike',
        'repost_photo' => 'App\\PostsLike',
        'repost_video' => 'App\\PostsLike',
    ];

    protected $statisticsTypes = [
        'post' => 'App\\Post',
        'repost_post' => 'App\\Post',
        'repost_photo' => 'App\\Post',
        'repost_video' => 'App\\Post',
        'repost_event' => 'App\\Post',
        'profile' => 'App\\User',
        'event' => 'App\\Event',
        'general' => ''
    ];

    public function getCommentTypes()
    {
        return $this->commentTypes;
    }

    public function getPostTypes()
    {
        return $this->postTypes;
    }

    public function getMediaTypesInPlural()
    {
        $data = [];
        foreach ($this->getMediaTypes() as $key => $value) {
            $data[$key . 's'] = $value;
        }

        return $data;
    }

    public function getMediaTypes()
    {
        return $this->mediaTypes;
    }

    public function getLikeTypes()
    {
        return $this->likeTypes;
    }

    public function getStatisticsTypes()
    {
        return $this->statisticsTypes;
    }
}
