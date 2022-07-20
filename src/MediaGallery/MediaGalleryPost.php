<?php

namespace DevAnime\SatoruGojo\MediaGallery;
use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\MediaGallery\Item\PhotoItem;
use DevAnime\SatoruGojo\MediaGallery\Item\VideoItem;

/**
 * Class MediaGalleryPost
 * @package DevAnime\SatoruGojo\MediaGallery
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property bool $photo_captions
 */
class MediaGalleryPost extends PostBase
{
    const POST_TYPE = 'media-gallery';
    public $video_captions;
    /**
     * @var MediaGallery
     */
    public $gallery;

    protected function init()
    {
        $this->video_captions = intval($this->field('video_thumbnail_captions'));
        $this->buildGallery($this->field('media') ?: []);
        if (!$this->featuredImage() && ($first_thumbnail = $this->gallery->getThumbnailImage())) {
            $this->setFeaturedImage(clone $first_thumbnail);
        }
    }

    protected function buildGallery($field = [])
    {
        $this->gallery = new MediaGallery();
        foreach ($field as $flex) {
            $this->addToGallery([VideoItem::class, 'createFromId'], (array) ($flex['videos'] ?? []), (int) $this->video_captions);
            $this->addToGallery([PhotoItem::class, 'createFromId'], array_column((array) ($flex['photos'] ?? []), 'ID'), (int) $this->photo_captions);
        }
    }

    protected function addToGallery(callable $factory, array $items = [], int $caption_type = 0)
    {
        foreach ($items as $item) {
            try {
                $MediaGalleryItem = $factory($item, $caption_type);
                $this->gallery = $this->gallery->add($MediaGalleryItem);
            }
            catch (\Throwable $e) {
                error_log($e->getMessage());
            }
        }
    }
}
