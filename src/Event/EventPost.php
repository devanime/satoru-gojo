<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\MediaGallery\MediaGalleryPost;
use DevAnime\Support\DateFormat;
use DevAnime\Support\DateRange;
use DevAnime\Support\DateTime;
use DevAnime\View\Link;

/**
 * Class EventPost
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property array $ticketing_link
 * @property string $price_range
 * @property array $other_ticketing_links
 * @property PerformanceCollection $performances
 * @property string $duration
 * @property int $intermission
 * @property MediaGalleryPost $media_gallery
 * @property array $reviews
 * @property string $venue_id
 * @property \WP_Term[] $genres
 * @property array $genre_list
 * @property DateTime $start_date
 * @property DateTime $end_date
 * @property Link $venue_link
 */
class EventPost extends PostBase
{
    const POST_TYPE = 'event';
    const META_KEY_START_DATE = 'start_date';
    const META_KEY_END_DATE = 'end_date';
    protected $_date_field_names = [self::META_KEY_START_DATE, self::META_KEY_END_DATE];

    /**
     * @return PerformanceCollection
     */
    public function get_performances()
    {
        $performances = $this->field('performances') ?: [];
        if (is_array($performances)) {
            $this->performances = $performances = PerformanceCollection::createFromPerformanceRows($performances);
        }
        return $performances;
    }

    public function get_other_ticketing_links(): array
    {
        $links = $this->field('other_ticketing_links');
        return !empty($links) ? array_column($links, 'link') : [];
    }

    /**
     * @return MediaGalleryPost|null
     */
    public function get_media_gallery()
    {
        $media_gallery = $this->field('media_gallery') ?: null;
        if (is_numeric($media_gallery)) {
            $this->media_gallery = $media_gallery = new MediaGalleryPost($media_gallery);
        }
        return $media_gallery;
    }

    public function getVenue()
    {
        return $this->venue_id ? new Venue($this->venue_id) : null;
    }

    public function get_genre_list(): array
    {
        return array_column($this->terms('genre'), 'name');
    }

    public function hasGenre(): bool
    {
        return !empty($this->genre_list);
    }

    public function getVenueLink()
    {
        if ($this->field('external_venue')) {
            return Link::createFromField($this->field('external_venue_link'));
        }
        if ($Venue = $this->getVenue()) {
            return Link::createFromPostPermalink($Venue);
        }
        return null;
    }

    /**
     * @param DateFormat $date_format
     * @param string $separator
     *
     * @return string
     */
    public function getStartToEndDateRange(DateFormat $date_format = null, $separator = ' - ')
    {
        return $this->start_date && $this->end_date ?
            new DateRange(
                new DateTime($this->start_date),
                new DateTime($this->end_date),
                $date_format,
                $separator
            ) : '';
    }
}
