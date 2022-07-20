<?php

namespace DevAnime\SatoruGojo\Video;

use DevAnime\Repositories\PostRepository;

/**
 * Class VideoRepository
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class VideoRepository extends PostRepository
{
    protected $model_class = VideoPost::class;

}
