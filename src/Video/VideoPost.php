<?php

namespace DevAnime\SatoruGojo\Video;
use DevAnime\Models\PostBase;
use DevAnime\Util;

/**
 * Class VideoPost
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property string $video_id
 * @property string $post_title
 * @property string $post_content
 * @property string $post_name
 * @property string $duration
 * @property string $link
 * @property string $video
 * @property string $image_url
 * @property string $provider_image_id
 * @property string $service
 * @property string $width
 * @property string $height
 * @property string $thumbnail_brightness
 * @property bool $override_dimensions
 *
 */
class VideoPost extends PostBase
{
    const POST_TYPE = 'video';

    public function get_video()
    {
        return get_field('video', $this->post()->ID, false); // false returns original embed URL (check WP embed object for hook)
    }

    public function hasVideo()
    {
        return !empty($this->video);
    }

    public function setMetaDataFromVideoProviderMetaData(VideoProviderMetaData $VideoProviderMetaData)
    {
        if (empty($this->post_title)) {
            $this->post_title = $VideoProviderMetaData->title;
        }
        if (empty($this->post_content)) {
            $this->post_content = $VideoProviderMetaData->description;
        }
        $this->post_name = Util::toSnakeCase(str_replace(' ', '', $VideoProviderMetaData->title));
        $this->video_id = $VideoProviderMetaData->id;
        $this->duration = $VideoProviderMetaData->duration;
        $this->link = $VideoProviderMetaData->link;
        $this->image_url = $VideoProviderMetaData->image_url;
        if (!$this->override_dimensions) {
            $this->width = $VideoProviderMetaData->width;
            $this->height = $VideoProviderMetaData->height;
        }
        $this->service = $VideoProviderMetaData->service;
    }

    public function setFeaturedImageFromProvider($image)
    {
        $this->provider_image_id = $image->ID;
        $this->setFeaturedImage($image);
    }

    public function getSlug()
    {
        return $this->post()->post_name;
    }
}
