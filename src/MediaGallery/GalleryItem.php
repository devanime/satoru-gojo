<?php
/**
 * Class GalleryItem
 * @package DevAnime\SatoruGojo\MediaGallery
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo\MediaGallery;

abstract class GalleryItem
{
    const TYPE = null;
    const CAPTION_TYPE_NONE = 0;

    protected $id;
    protected $caption;
    protected $type;
    protected $item;

    public function getType()
    {
        return static::TYPE;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getId(): int
    {
        return $this->id;
    }

    abstract public function getImage();

    abstract public static function createFromID(int $id, int $caption_type = self::CAPTION_TYPE_NONE);
}
