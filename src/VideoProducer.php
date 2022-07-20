<?php

namespace DevAnime\SatoruGojo;

use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\SatoruGojo\Video\VideoDTO;
use DevAnime\SatoruGojo\Video\VideoPost;
use DevAnime\SatoruGojo\Video\VideoService;
use DevAnime\View\AdminNotification;

/**
 * Class VideoProducer
 * @package DevAnime\SatoruGojo
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class VideoProducer extends Producer
{
    const COMPONENT = 'video';
    protected $model_classes = [VideoPost::class];
    /** @var VideoPost[] */
    protected static $_video_cache = [];

    public function __construct()
    {
        parent::__construct();
        add_action('acf/save_post', [$this, 'saveVideo']);
        add_action('wp_footer', [$this, 'unstashVideos']);
        AdminNotification::init();
    }

    public function saveVideo($post_id)
    {

        global $wpdb;
        $post = get_post($post_id);
        if ($post->post_type !== VideoPost::POST_TYPE) {
            return;
        }
        $VideoService = new VideoService();
        $result = $VideoService->addVideoMetadataToVideoPost(new VideoPost($post_id));
        if (!$result) {
            $wpdb->update($wpdb->posts, ['post_status' => 'draft'], ['ID' => $post_id], '%s', '%d');
            wp_transition_post_status('draft', $post->post_status, $post);
            clean_post_cache($post);
            add_filter('redirect_post_location', function($location) {
                return remove_query_arg('message', $location);
            });

        }
    }

    public function unstashVideos()
    {
        $video_cache = [];
        foreach (static::$_video_cache as $videoPost) {
            $video_cache[$videoPost->ID] = VideoDTO::createFromVideoPost($videoPost);
        }
        $video_cache = apply_filters('satoru-gojo/video/cache', $video_cache);
        if (! empty($video_cache)) {
            echo '<script>var videoCache = ' . json_encode($video_cache) . '</script>';
        }
    }

    public static function cacheVideo(VideoPost $videoPost)
    {
        try {
            static::$_video_cache[$videoPost->ID] = $videoPost;
        } catch (\Throwable $e) {
            error_log('Error Loading Video into cache: ' . $e->getMessage());
        }
    }


}
