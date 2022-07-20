<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Support\DateTime;

/**
 * Class Performance
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class Performance
{
    protected $datetime;
    protected $ticketing_url;
    protected $availability;
    protected $is_sold_out = false;

    public function __construct(string $datetime, string $ticketing_url = '', string $availability_id = '')
    {
        $this->datetime = new DateTime($datetime);
        $this->ticketing_url = $ticketing_url;
        if ($availability_id) {
            $this->availability = get_term_field('name', $availability_id, 'availability');
            $this->is_sold_out = (bool) get_term_meta($availability_id, 'sold_out_state', true);
        }
    }

    /**
     * @param array $row
     * @return Performance
     */
    public static function createFromRow(array $row)
    {
        return new static($row['datetime'], $row['ticketing_url'], $row['availability_id']);
    }

    public function getFormattedDateTime(string $format): string
    {
        return $this->datetime->format($format);
    }

    public function getDateTime(): DateTime
    {
        return $this->datetime;
    }

    public function getTicketingUrl(): string
    {
        return $this->ticketing_url;
    }

    public function getAvailability(): string
    {
        return $this->availability;
    }

    public function isAvailable(): bool
    {
        return !$this->is_sold_out;
    }

    public function isSoldOut(): bool
    {
        return $this->is_sold_out;
    }

    public function __toString(): string
    {
        return $this->datetime;
    }

    /**
     * Pull in targeted method from DateTime to the Performance.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args = [])
    {
        if (method_exists($this->datetime, $method)) {
            return call_user_func_array([$this->datetime, $method], $args);
        }
        throw new \BadMethodCallException($method);
    }
}
