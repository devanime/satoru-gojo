<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Repositories\PostRepository;

/**
 * Class VenueRepository
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class VenueRepository extends PostRepository
{
    protected $model_class = Venue::class;
}
