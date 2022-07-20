<?php

namespace DevAnime\SatoruGojo\Video;

use DevAnime\Support\DateTime;
use Madcoda\Youtube\Youtube;

/**
 * Class YoutubeVideoProvider
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class YoutubeVideoProvider extends VideoProvider
{
    const SERVICE = 'youtube';

    protected function setVideoMeta()
    {
        $APIObject = $this->getAPIObject();

        $this->videoMeta = $this->getVideoProviderMetaData($this->videoID, $APIObject->getVideoInfo($this->videoID));
    }

    public function getVideoProviderMetaData($id, $object): VideoProviderMetaData
    {
        $start = new DateTime('@0');
        $duration = '';
        try {
            $start->add(new \DateInterval($object->contentDetails->duration));
            $duration = $start->format('H:i:s');
        } catch (\Exception $e) {
            $this->addWarning($e->getMessage());
        }
        $thumbs = (array)$object->snippet->thumbnails;
        usort($thumbs, function($a, $b) { return $a->width - $b->width; });
        $thumb = array_pop($thumbs);
        preg_match('/width="(\d+)" height="(\d+)"/', $object->player->embedHtml, $matches);
        list($_, $width, $height) = $matches;

        return new VideoProviderMetaData([
            'id'          => $object->id,
            'title'       => $object->snippet->title,
            'description' => $object->snippet->description,
            'duration'    => $duration,
            'link'        => 'https://www.youtube.com/watch?v=' . $id,
            'image_url'   => $thumb->url,
            'height'      => is_numeric($height) ? $height : $thumb->height,
            'width'       => is_numeric($width) ? $width : $thumb->width,
            'service'     => static::SERVICE
        ]);
    }

    protected function getAPIObject()
    {
        $key = $this->getCredentials();

        return new Youtube(['key' => $key]);
    }

    private function getCredentials()
    {
        return get_field('youtube_api_key',
            'option') ?: (defined('VIDEO_PRODUCER_YOUTUBE_API_KEY') ? VIDEO_PRODUCER_YOUTUBE_API_KEY : false);
    }
}
