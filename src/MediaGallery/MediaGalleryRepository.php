<?php

namespace DevAnime\SatoruGojo\MediaGallery;
use DevAnime\Repositories\PostRepository;

/**
 * Class VideoRepository
 * @package DevAnime\SatoruGojo\MediaGallery
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class MediaGalleryRepository extends PostRepository
{
    protected $model_class = MediaGalleryPost::class;
}
