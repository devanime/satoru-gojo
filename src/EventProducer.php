<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Event\EventFilters;
use DevAnime\SatoruGojo\Event\VenueRepository;
use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\SatoruGojo\Event\EventRestController;
use DevAnime\Support\DateTime;
use Theme\Event\EventPost;

/**
 * Class EventProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class EventProducer extends Producer
{
    const COMPONENT = 'event';
    protected $model_classes = [EventPost::class];
    protected static $default_meta_query = [
        'date_clause' => ['key' => 'end_date']
    ];

    public function __construct()
    {
        parent::__construct();
        new EventRestController();
        add_filter('devanime/admin_col/venue_id/event', [$this,'formatVenueAdminColumn']);
        add_filter('devanime/admin_col/start_date/event', [$this,'formatDatesAdminColumn'], 10, 2);
        add_action('after_save_post_event', [$this, 'setEventDates']);
        add_filter('pre_get_posts', [$this, 'sortEvents']);
    }

    public function formatVenueAdminColumn($id): string
    {
        $VenueRepository = new VenueRepository();
        if ($venue_id = is_array($id) ? reset($id) : $id) {
            $Venue = $VenueRepository->findById($venue_id);
            return sprintf(
                '<a href="%1$s" target="_blank">%2$s</a>',
                get_edit_post_link($Venue->ID),
                $Venue->title()
            );
        }
        return '';
    }

    public function formatDatesAdminColumn($content, $post_id): string
    {
        $Event = new EventPost($post_id);
        return $Event->getStartToEndDateRange();
    }

    public function setEventDates($post_id)
    {
        $Event = new EventPost($post_id);
        $NextPerformance = $Event->performances->findFirst();
        $LastPerformance = $Event->performances->findLast();
        $fallback_date = new DateTime('midnight');
        $start_date = $NextPerformance ? $NextPerformance->getDateTime() : $fallback_date;
        update_post_meta($post_id, EventPost::META_KEY_START_DATE, $start_date->format('Y-m-d H:i:s'));
        $end_date = $LastPerformance ? $LastPerformance->getDateTime() : $fallback_date;
        update_post_meta($post_id, EventPost::META_KEY_END_DATE, $end_date->format('Y-m-d H:i:s'));
    }

    public function sortEvents(\WP_Query $query)
    {
        if (!$query->is_singular() && (in_array(EventPost::POST_TYPE, (array) $query->get('post_type')))) {
            static::$default_meta_query['date_clause'] = array_merge(static::$default_meta_query['date_clause'], $this->getSortQueryArgs());
            $query->set('meta_query', !empty($query->query_vars['meta_query']) ? array_merge($query->query_vars['meta_query'], static::$default_meta_query) : static::$default_meta_query);
            $query->set('orderby', 'date_clause');
            $query->set('order', 'ASC');
        }

        return $query;
    }

    protected function getSortQueryArgs()
    {
        return !is_admin() ?
            ['value' => date('Y-m-d h:i'), 'compare' => '>'] : ['compare' => 'EXISTS'];
    }
}
