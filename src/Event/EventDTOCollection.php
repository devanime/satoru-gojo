<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Models\DTO;
use DevAnime\Models\DTOCollection;

/**
 * Class EventDTOCollection
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class EventDTOCollection extends DTOCollection
{
    protected function createDTO($item): DTO
    {
        return new EventDTO($item);
    }
}
