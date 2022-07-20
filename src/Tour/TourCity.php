<?php

namespace DevAnime\SatoruGojo\Tour;
use DevAnime\Models\PostBase;
use DevAnime\Support\DateTime;

/**
 * Class TourCity
 * @package Bacsktage\Producer\Tour
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property DateTime $start_date
 * @property DateTime $end_date
 * @property string $date_replacement_text
 * @property string $venue_name
 * @property string $venue_url
 * @property string $cta_title
 * @property string $cta_url
 */
class TourCity extends PostBase
{
    const POST_TYPE = 'tour-city';
    protected $_date_field_names = ['start_date', 'end_date'];
}
