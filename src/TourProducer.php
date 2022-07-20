<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\SatoruGojo\Tour\TourCity;
use DevAnime\Support\DateTime;

/**
 * Class TourProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TourProducer extends Producer
{
    const COMPONENT = 'tour';
    const ADMIN_FORMAT = 'n/j/y';

    public function __construct()
    {
        parent::__construct();
        add_filter('devanime/admin_col/start_date/tour-city', [$this,'formatDate'], 10, 2);
        add_filter('devanime/admin_col/end_date/tour-city', [$this,'formatDate'], 10, 2);
    }

    public function formatDate($content)
    {
        $date = reset($content);
        if (!empty($date)) {
            $date = new DateTime($date, static::ADMIN_FORMAT);
        }
        return $date;
    }
}
