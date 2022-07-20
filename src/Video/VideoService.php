<?php

namespace DevAnime\SatoruGojo\Video;

use DevAnime\View\AdminNotification;

/**
 * interface VideoService
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class VideoService
{
    protected static $services = [
        VimeoVideoProvider::SERVICE   => VimeoVideoProvider::class,
        YoutubeVideoProvider::SERVICE => YoutubeVideoProvider::class
    ];


    public function addVideoMetaDataToVideoPost(VideoPost $VideoPost)
    {
        $VideoRepository = new VideoRepository();
        try {
            $VideoMetaData = $this->getVideoMetadataFromUrl($VideoPost);
            $VideoPost->setMetaDataFromVideoProviderMetaData($VideoMetaData);
            if (! $VideoPost->featuredImage() instanceof \WP_Image) {
                $provider_image = $this->getProviderImageFromVideoPost($VideoPost);
                if($provider_image instanceof \WP_Image) {
                    $VideoPost->setFeaturedImageFromProvider($provider_image);
                }
            }
            $result = $VideoRepository->add($VideoPost);
            if (is_wp_error($result)) {
                throw new \Exception($result->get_error_message());
            }
            return $result;
        } catch (\Throwable $e) {
            $this->notify($e->getMessage(), $VideoPost);
        }
        return false;
    }

    protected function notify(string $message, VideoPost $VideoPost, string $type = AdminNotification::ERROR)
    {
        AdminNotification::dispatch(sprintf('Video %s: %s', $type, $message), $type);
        if ($type != AdminNotification::SUCCESS) {
            error_log(sprintf('VideoService %s for video post #%d: %s', $type, $VideoPost->ID, $message));
        }
    }

    public function getVideoMetadataFromUrl(VideoPost $VideoPost): VideoProviderMetaData
    {
        $Service = static::getVideoService($VideoPost->video);
        $warnings = $Service->getWarnings();
        array_walk($warnings, function($message) use ($VideoPost) {
            $this->notify($message, $VideoPost, AdminNotification::WARNING);
        });
        return $Service->videoMeta;
    }

    public static function getVideoService($url): VideoProvider
    {
        $url_parser = new URLParser($url);
        if (! $url_parser->html) {
            throw new \InvalidArgumentException('Invalid Video Url: ' . $url);
        }
        if (empty(self::$services[$url_parser->provider_name])) {
            throw new \InvalidArgumentException($url_parser->provider_name . ' not supported');
        }

        return new self::$services[$url_parser->provider_name]($url_parser);
    }

    public function getProviderImageFromVideoPost(VideoPost $VideoPost)
    {
        $image = \WP_Image::create_from_url($VideoPost->image_url);
        if (is_wp_error($image)) {
            throw new \Exception($image->get_error_message());
        }
        if (! empty($VideoPost->provider_image_id)) {
            wp_delete_attachment($VideoPost->provider_image_id, true);
        }
        return $image;
    }

}
