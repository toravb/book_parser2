<?php

namespace App\Services;

use App\Interfaces\PolymorphTypes;

class PolymorphTypesGenerator implements PolymorphTypes
{
    protected $polymorphTypes = [
        'post' => 'App\\Post',
        'event' => 'App\\Event',
        'photo' => 'App\\MediaPhoto',
        'report_post' => 'App\\OriginalPost',
        'video' => 'App\\Video',
    ];

    protected $modelsForCheckRepostTypes = [
        'post' => 'App\\Post',
        'event' => 'App\\Event',
        'photo' => 'App\\MediaPhoto',
        'video' => 'App\\Video',
        'repost_post' => 'App\\Post',
        'repost_event' => 'App\\Post',
        'repost_photo' => 'App\\Post',
        'repost_video' => 'App\\Post',
    ];

    protected $repostTypes = [
        'post' => 'App\\OriginalPost',
        'event' => 'App\\Event',
        'photo' => 'App\\MediaPhoto',
        'video' => 'App\\Video',
        'repost_post' => 'App\\OriginalPost',
        'repost_event' => 'App\\Event',
        'repost_photo' => 'App\\MediaPhoto',
        'repost_video' => 'App\\Video',
    ];


    public function getTypes()
    {
        return $this->polymorphTypes;
    }

    public function getRepostTypes()
    {
        return $this->repostTypes;
    }

    public function checkTypeIdForRepost()
    {
        return $this->modelsForCheckRepostTypes;
    }
}
