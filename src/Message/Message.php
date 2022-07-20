<?php

namespace DevAnime\SatoruGojo\Message;

use DevAnime\Support\DateTime;

/**
 * Class Message
 * @package DevAnime\SatoruGojo\Message
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property string $id
 * @property string $style
 * @property string $display_type
 * @property boolean $enabled
 * @property string $modal_style
 * @property string $modal_id
 * @property string $image_id
 * @property string $image_mobile_id
 * @property string $heading
 * @property string $content
 * @property array $link
 * @property DateTime $start_date
 * @property DateTime $end_date
 */
class Message
{
    const OPTIONS = 'option';
    const DEFAULT_STYLE = 'info';
    const DEFAULT_DISPLAY = 'banner';
    const DEFAULT_MODAL_ID = 'global';
    const DEFAULT_START = '-1 year';
    const DEFAULT_END = '+1 year';
    const DEFAULT_EXPIRY = [
        'days' => 0,
        'hours' => 1
    ];

    protected $id;
    protected $enabled = false;
    protected $style = '';
    protected $display_type = 'banner';
    protected $modal_style = 'box';
    protected $modal_id = 'message';
    protected $image_id = '';
    protected $image_mobile_id = '';
    protected $heading = '';
    protected $content = '';
    protected $link = [];
    protected $modal_trigger = '';
    protected $modal_trigger_delay = 0;
    protected $modal_trigger_distance = 0;
    protected $page_id = 0;
    protected $start_date;
    protected $end_date;
    protected $expiry; // Time in seconds

    public function __construct(string $id, DateTime $start_date, DateTime $end_date, string $content, int $expiry = 0)
    {
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->content = $content;
        $this->expiry = $expiry;
    }

    public static function createFromAssignment(array $assignment)
    {
        $id = $assignment['message_display'] == static::DEFAULT_DISPLAY ? static::OPTIONS : static::DEFAULT_MODAL_ID;
        $start = $assignment['datetime_start'] ?: static::DEFAULT_START;
        $end = $assignment['datetime_end'] ?: static::DEFAULT_END;
        $expiry = array_merge(static::DEFAULT_EXPIRY, $assignment['cookie_expiry'] ?: []);
        $display = $assignment['message_display'] ?: static::DEFAULT_DISPLAY;
        $content = $assignment['content'];

        $Message = new static(
            $id,
            new DateTime($start),
            new DateTime($end),
            (string)$content,
            static::getExpiration($expiry['days'], $expiry['hours'])
        );

        $Message->enable()->setDisplayType($display);
        // Repurpose default modal id
        if ($display !== static::DEFAULT_DISPLAY) {
            $Message->setModalId('message-modal-global');
        }
        if ($style = $assignment['banner_style']) {
            $Message->setStyle($style);
        }
        if ($image_id = $assignment['modal_image']) {
            $Message->setImageId($image_id);
        }
        if ($image_mobile_id = $assignment['modal_mobile_image']) {
            $Message->setImageMobileId($image_mobile_id);
        }
        if ($heading = $assignment['modal_heading']) {
            $Message->setHeading($heading);
        }
        if ($modal_style = $assignment['modal_style']) {
            if ($modal_style === 'existing' && ($modal_id = $assignment['modal_id'])) {
                $Message->setModalId($modal_id);
            } else {
                $Message->setModalStyle($modal_style);
            }
        }
        if ($modal_trigger = $assignment['modal_trigger']) {
            $Message->setModalTrigger($modal_trigger);
        }
        if ($modal_trigger_delay = $assignment['modal_trigger_delay']) {
            $Message->setModalTriggerDelay(intval($modal_trigger_delay));
        }
        if ($modal_trigger_distance = $assignment['modal_trigger_distance']) {
            $Message->setModalTriggerDistance(intval($modal_trigger_distance));
        }
        if ($location = $assignment['location']) {
            // replace ID with location ID
            $Message->setId($location[0]);
            $Message->setModalId('modal-message-' . $location[0]);
            $Message->setModalLocation($location[0]);
        }
        if (!get_field('enable_messaging', static::OPTIONS)) {
            $Message->disable();
        }
        return $Message;

    }

    /**
     * Use static::createFromGlobalAssignment($assignment)
     * @return Message|static
     * @throws \Exception
     *
     * @deprecated
     *
     */
    public static function createFromOptions()
    {
        /* @var Message $Message ; */
        $start = get_field('message_start', static::OPTIONS) ?: static::DEFAULT_START;
        $end = get_field('message_end', static::OPTIONS) ?: static::DEFAULT_END;
        $expiry = array_merge(static::DEFAULT_EXPIRY, get_field('message_expiry', static::OPTIONS) ?: []);
        $display = get_field('message_display', static::OPTIONS) ?: static::DEFAULT_DISPLAY;
        $use_modal = $display === 'modal';
        $content = $use_modal ? get_field('message_wysiwyg', static::OPTIONS) : get_field('message_content',
            static::OPTIONS);

        $Message = new static(
            static::OPTIONS,
            new DateTime($start),
            new DateTime($end),
            (string)$content,
            static::getExpiration($expiry['days'], $expiry['hours'])
        );
        $Message->enable()->setDisplayType($display);
        if ($style = get_field('message_style', static::OPTIONS)) {
            $Message->setStyle($style);
        }
        if ($image_id = get_field('message_image', static::OPTIONS)) {
            $Message->setImageId($image_id);
        }
        if ($image_mobile_id = get_field('message_image_mobile', static::OPTIONS)) {
            $Message->setImageMobileId($image_mobile_id);
        }
        if ($heading = get_field('message_heading', static::OPTIONS)) {
            $Message->setHeading($heading);
        }
        if ($modal_style = get_field('message_modal_style', static::OPTIONS)) {
            if ($modal_style === 'existing' && ($modal_id = get_field('message_modal_id', static::OPTIONS))) {
                $Message->setModalId($modal_id);
            } else {
                $Message->setModalStyle($modal_style);
            }
        }
        if ($modal_trigger = get_field('message_modal_trigger', static::OPTIONS)) {
            $Message->setModalTrigger($modal_trigger);
        }
        if ($modal_trigger_delay = get_field('message_modal_trigger_delay', static::OPTIONS)) {
            $Message->setModalTriggerDelay(intval($modal_trigger_delay));
        }
        if ($modal_trigger_distance = get_field('message_modal_trigger_distance', static::OPTIONS)) {
            $Message->setModalTriggerDistance(intval($modal_trigger_distance));
        }
        if ($link = get_field('message_link', static::OPTIONS)) {
            $Message->setLink($link);
        }
        if (!get_field('enable_messaging', static::OPTIONS)) {
            $Message->disable();
        }
        if ($pages = get_field('message_pages', static::OPTIONS)) {
            $currentPage = get_the_ID();
            if (!in_array($currentPage, $pages)) {
                $Message->disable();
            }
        }

        return $Message;
    }

    // Setters

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setStyle(string $style)
    {
        $this->style = $style;

        return $this;
    }

    public function setDisplayType(string $displayType)
    {
        $this->display_type = $displayType;

        return $this;
    }

    public function setModalStyle(string $modal_style): Message
    {
        $this->modal_style = $modal_style;

        return $this;
    }

    public function setModalId(string $modal_id): Message
    {
        $this->modal_id = $modal_id;

        return $this;
    }

    public function setModalTrigger(string $modal_trigger): Message
    {
        $this->modal_trigger = $modal_trigger;

        return $this;
    }

    public function setModalTriggerDelay(int $modal_trigger_delay): Message
    {
        $this->modal_trigger_delay = $modal_trigger_delay;

        return $this;
    }

    public function setModalTriggerDistance(int $modal_trigger_distance): Message
    {
        $this->modal_trigger_distance = $modal_trigger_distance;

        return $this;
    }

    public function setModalLocation($page_id): Message
    {
        $this->page_id = $page_id;
        return $this;
    }

    public function setImageId(string $image_id): Message
    {
        $this->image_id = $image_id;

        return $this;
    }

    public function setImageMobileId(string $image_mobile_id): Message
    {
        $this->image_mobile_id = $image_mobile_id;

        return $this;
    }

    public function setHeading(string $heading): Message
    {
        $this->heading = $heading;

        return $this;
    }

    public function setLink(array $link)
    {
        $this->link = $link;

        return $this;
    }

    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function getDisplayType(): string
    {
        return $this->display_type;
    }

    public function getModalStyle(): string
    {
        return $this->modal_style;
    }

    public function getModalId(): string
    {
        return $this->modal_id;
    }

    public function getModalTrigger(): string
    {
        return $this->modal_trigger;
    }

    public function getModalTriggerDelay(): int
    {
        return $this->modal_trigger_delay;
    }

    public function getModalTriggerDistance()
    {
        return $this->modal_trigger_distance;
    }

    public function getModalLocation()
    {
        return $this->page_id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getImageId(): string
    {
        return $this->image_id;
    }

    public function getImageMobileId(): string
    {
        return $this->image_mobile_id;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getStart()
    {
        return $this->start_date;
    }

    public function getEnd()
    {
        return $this->end_date;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    // Helpers

    public static function getExpiration($days, $hours)
    {
        return (86400 * intval($days)) + (3600 * intval($hours));
    }

    public function shouldDisplay()
    {
        if (!$this->enabled || empty($this->content)) {
            return false;
        }
        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    public function isDisplayTypeBanner()
    {
        return $this->display_type == 'banner';
    }

    public function isDisplayTypeModal()
    {
        return $this->display_type == 'modal';
    }

    public function isGlobalModal()
    {
        return $this->isDisplayTypeModal() && $this->getModalLocation() == 0 && $this->getId() == 'global';
    }

    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    public function disable()
    {
        $this->enabled = false;

        return $this;
    }

    public function setGlobalStyle()
    {
        $this->style = get_field('message_style', static::OPTIONS) ?: static::DEFAULT_STYLE;

        return $this;
    }

    // Object specific

    public function __toString()
    {
        return (string)$this->content;
    }
}
