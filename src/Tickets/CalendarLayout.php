<?php

namespace DevAnime\SatoruGojo\Tickets;

use DevAnime\Models\OptionsBase;

/**
 * Class CalendarLayout
 * @package DevAnime\SatoruGojo\Tickets
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 *
 * @method static calendarHeadline()
 * @method static calendarTagline()
 * @method static calendarTicketingTips()
 * @method static calendarAdditionalTips()
 */
class CalendarLayout extends OptionsBase
{
    protected $default_values = [
        'calendar_headline' => '',
        'calendar_tagline' => '',
        'calendar_ticketing_tips' => [],
        'calendar_additional_tips' => []
    ];
}
