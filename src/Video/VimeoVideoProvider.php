<?php

namespace DevAnime\SatoruGojo\Video;

use Vimeo\Vimeo;

/**
 * Class VimeoVideoProvider
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class VimeoVideoProvider extends VideoProvider
{
    const SERVICE = 'vimeo';

    protected function setVideoMeta()
    {
        $APIObject = $this->getAPIObject();
        $request = $APIObject->request('/videos/' . str_replace('/', ':', $this->videoID));
        $this->videoMeta = $this->getVideoProviderMetaData($request['body']);
    }


    public function getVideoProviderMetaData(array $atts): VideoProviderMetaData
    {
        if (empty($atts['link'])) {
            return new VideoProviderMetaData();
        }
        $time = round($atts['duration']);
        $thumbs = [];
        foreach ($atts['pictures']['sizes'] as $picture) {
            $thumbs[$picture['width']] = $picture['link'];
        }
        ksort($thumbs);
        $thumbnail = array_pop($thumbs);
        $thumbnail = explode('?', $thumbnail);
        $thumbnail = array_shift($thumbnail);
        preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $thumbnail, $matches);
        if (! $matches) {
            $thumbnail = $thumbnail . '.jpg';
        }

        return new VideoProviderMetaData([
            'id'          => $this->videoID,
            'title'       => $atts['name'],
            'description' => $atts['description'],
            'duration'    => sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), ($time % 60)),
            'link'        => $atts['link'],
            'image_url'   => $thumbnail,
            'height'      => $atts['height'],
            'width'       => $atts['width'],
            'service'     => static::SERVICE
        ]);
    }

    protected function getAPIObject()
    {
        return new Vimeo($this->getClientID(), $this->getClientSecret(), $this->getAccessToken());
    }

    private function getClientID()
    {
        return get_field('vimeo_client_id',
            'option') ?: (defined('VIDEO_PRODUCER_VIMEO_CLIENT_ID') ? VIDEO_PRODUCER_VIMEO_CLIENT_ID : false);
    }

    private function getClientSecret()
    {
        return get_field('vimeo_client_secret',
            'option') ?: (defined('VIDEO_PRODUCER_VIMEO_CLIENT_SECRET') ? VIDEO_PRODUCER_VIMEO_CLIENT_SECRET : false);
    }

    private function getAccessToken()
    {
        return get_field('vimeo_access_token',
            'option') ?: (defined('VIDEO_PRODUCER_VIMEO_ACCESS_TOKEN') ? VIDEO_PRODUCER_VIMEO_ACCESS_TOKEN : false);
    }
}
