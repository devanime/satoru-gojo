<?php
/**
 * Class VideoItem
 * @package DevAnime\SatoruGojo\MediaGallery\Item
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo\MediaGallery\Item;

use DevAnime\SatoruGojo\MediaGallery\GalleryItem;
use DevAnime\SatoruGojo\Video\VideoPost;

class VideoItem extends GalleryItem
{
    const TYPE = 'video';
    const CAPTION_TYPE_TITLE = 1;
    const CAPTION_TYPE_THUMBNAIL = 2;

    public function __construct(VideoPost $post, $caption_type = self::CAPTION_TYPE_NONE)
    {
        $this->item = $post;
        $this->id = (int) $post->ID;
        $this->setCaption($caption_type);
    }

    public function getImage()
    {
        return $this->item->featuredImage();
    }

    public static function createFromID(int $post_id, int $caption_type = self::CAPTION_TYPE_NONE)
    {
        $Video = new VideoPost($post_id);
        return $Video->hasVideo() ? new static($Video, $caption_type) : null;
    }

    protected function setCaption($caption_type)
    {
        $caption = '';
        switch ($caption_type) {
            case static::CAPTION_TYPE_TITLE:
                $caption = $this->item->title();
                break;
            case static::CAPTION_TYPE_THUMBNAIL:
                $image = $this->getImage();
                $caption = $image ? $image->caption: '';
                break;
            case static::CAPTION_TYPE_NONE:
            default:
                break;
        }
        $this->caption = $caption;
    }
}
