<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Models\PostBase;

/**
 * Class Venue
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property string $address
 * @property string $google_maps_link
 */
class Venue extends PostBase
{
    const POST_TYPE = 'venue';

    /**
     * @param EventPost $Event
     * @return Venue
     */
    public static function createFromEvent(EventPost $Event)
    {
        return new static($Event->venue_id);
    }
}
