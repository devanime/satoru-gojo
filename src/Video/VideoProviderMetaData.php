<?php

namespace DevAnime\SatoruGojo\Video;
use DevAnime\Models\ObjectBase;

/**
 * Class VideoProviderMetaData
 * @package DevAnime\SatoruGojo\Video
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $duration
 * @property string $link
 * @property string $image_url
 */
class VideoProviderMetaData extends ObjectBase
{
    public $id;
    public $title;
    public $description;
    public $duration;
    public $link;
    public $image_url;
    public $service;
    public $height;
    public $width;
}
