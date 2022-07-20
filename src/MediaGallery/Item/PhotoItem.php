<?php
/**
 * Class PhotoItem
 * @package DevAnime\SatoruGojo\MediaGallery\Item
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo\MediaGallery\Item;

use DevAnime\SatoruGojo\MediaGallery\GalleryItem;
use WP_Image;

class PhotoItem extends GalleryItem
{
    const TYPE = 'photo';
    const CAPTION_TYPE_THUMBNAIL = 1;

    public function __construct(WP_Image $image, $caption_type = self::CAPTION_TYPE_NONE)
    {
        $this->item = $image;
        $this->id = (int) $image->ID;
        $this->caption = $caption_type == static::CAPTION_TYPE_THUMBNAIL ? $image->caption : '';
    }

    public function getImage(): WP_Image
    {
        return $this->item;
    }

    public static function createFromID(int $attachment_id, int $caption_type = self::CAPTION_TYPE_NONE): GalleryItem
    {
        $image = WP_Image::get_by_attachment_id($attachment_id);
        return $image instanceof WP_Image ? new static($image, $caption_type) : null;
    }
}