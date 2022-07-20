<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Models\DTO;

/**
 * Class EventDTO
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class EventDTO extends DTO
{
    protected $id;
    protected $ticketing_link;
    protected $price_range;
    protected $other_ticketing_links;
    protected $performances;
    protected $duration;
    protected $intermission;
    protected $venue_id;
    protected $genres;
    protected $start_date;
    protected $end_date;

    public function __construct(EventPost $Event)
    {
        $this->id = $Event->ID;
        $this->ticketing_link = $Event->ticketing_link;
        $this->price_range = $Event->price_range;
        $this->other_ticketing_links = $Event->other_ticketing_links;
        $this->performances = $Event->performances;
        $this->duration = $Event->duration;
        $this->intermission = $Event->intermission;
        $this->venue_id = $Event->venue_id;
        $this->genres = $Event->genre_list;
        $this->start_date = $Event->start_date;
        $this->end_date = $Event->end_date;
    }
}
