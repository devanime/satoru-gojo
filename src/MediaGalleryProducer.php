<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\SatoruGojo\MediaGallery\MediaGalleryPost;

/**
 * Class MediaGalleryProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class MediaGalleryProducer extends Producer
{
    const COMPONENT = 'media-gallery';
    protected $model_classes = [MediaGalleryPost::class];
}
