<?php

namespace DevAnime\SatoruGojo\MediaGallery;

use DevAnime\Models\ObjectCollection;
use WP_Image;

class MediaGallery extends ObjectCollection
{
    protected static $object_class_name = GalleryItem::class;

    /**
     * @param GalleryItem $item
     * @return string
     */
    protected function getObjectHash($item)
    {
        return md5($item->getId());
    }

    public function getThumbnailImage()
    {
        if (empty($this->items)) {
            return null;
        }
        $first_item = $this->items[0]; /* @var GalleryItem $first_item */;
        return $first_item->getImage();
    }

    protected function validateClass($item)
    {
        parent::validateClass($item);
        if (!$item->getImage() instanceof WP_Image) {
            throw new \InvalidArgumentException('Object passed to Gallery collection does not have a valid image. Post #' . $item->getId());
        }
    }

}
