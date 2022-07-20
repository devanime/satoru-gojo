<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Support\Producer;
use BU_Version, DS_Public_Post_Preview;
use WP_Post_Type;

/**
 * Class PreviewProducer
 * @package DevAnime\SatoruGojo
 *
 * Required plugins:
 * wpackagist-plugin/bu-versions
 * wpackagist-plugin/public-post-preview
 *
 */
class PreviewProducer extends Producer
{
    const COMPONENT = 'preview';

    const VERSION_ID = 'version_id';
    const PUBLIC_PREVIEW_HASH = '_ppp';

    protected static $excluded_post_types = ['acf-field-group', 'vc_grid_item', 'event'];

    protected $original_id;

    public function __construct()
    {
        parent::__construct();
        if (!(class_exists('BU_Version') && class_exists('DS_Public_Post_Preview'))) {
            return;
        }
        add_filter('ppp_nonce_life', function() {
            return 60 * 60 * 24 * 14; // 14 days
        });
        add_filter('bu_alt_versions_for_type', function ($_, WP_Post_Type $post_type) {
            if ($_) {
                $_ = ! in_array($post_type->name, static::$excluded_post_types);
            }

            return $_;
        }, 10, 2);

        if ($version = $this->getVersion()) {
            $this->original_id = $version->original->ID;
            $this->addPublicPreviewHooks();
        }
        //transform public preview nonce from clone to original upon redirecting
        add_filter('wp_redirect', [$this, 'convertPublicPreviewHash']);

        //for acf and other plugins, make sure page_alt gets all the page templates too
        add_filter('theme_page_alt_templates', function($post_templates, \WP_Theme $wp_theme, $post) {
            if (empty($post_templates)) {
                $version = $this->getVersion($post->ID);
                $post_templates = $wp_theme->get_page_templates($version->original);
            }
            return $post_templates;
        }, 10, 3);

    }

    public function convertPublicPreviewHash($location) {
        $query = $this->getQuery($location);
        $version = $this->getVersion($this->getVersionId($query));
        if ($version && $this->getPublicPreviewHash() && !$this->getPublicPreviewHash($query)) {
            $preview_query = $this->getQuery(DS_Public_Post_Preview::get_preview_link($version->original));
            $location = add_query_arg(static::PUBLIC_PREVIEW_HASH, $this->getPublicPreviewHash($preview_query), $location);
        }
        return $location;
    }

    public function addPublicPreviewHooks()
    {
        //allow any post status to be valid public previews
        add_filter('ppp_published_statuses', '__return_empty_array');
        //add published original post id to stored list of valid public preview post ids
        add_filter('option_public_post_preview', function($value) {
            return array_merge($value, [$this->original_id]);
        });
        //apply the_preview filter manually since normal operations require edit caps for preview
        add_filter('the_posts', function($posts, \WP_Query $query) {
            if ($posts && $query->is_preview) {
                $posts[0] = get_post(apply_filters_ref_array('the_preview', [$posts[0], $this]));
            }
            return $posts;
        }, 10, 2);

        //allow preview to be triggered on static home page
        add_action('pre_get_posts', function(\WP_Query $query) {
            if (!($query->is_preview() && $query->is_home())) {
                return;
            }
            add_action('template_redirect', function() use ($query) {
                $query->set('p', 0);
            }, 1);
            $front_page = get_option('page_on_front');
            if ($this->original_id == $front_page) {
                $query->is_singular = true;
                $query->is_page = true;
                $query->is_home = false;
                $query->set('page_id', $front_page);
            }
        }, 1);
    }

    protected function getQuery($url)
    {
        $query = [];
        wp_parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return $query;
    }

    protected function getVersionId($query = null)
    {
        return $this->getQueryVar(static::VERSION_ID, $query);
    }

    protected function getPublicPreviewHash($query = null)
    {
        return $this->getQueryVar(static::PUBLIC_PREVIEW_HASH, $query);
    }

    protected function getQueryVar($var, $query = null)
    {
        if (is_null($query)) {
            $query = $_GET;
        }
        return $query[$var] ?? false;
    }

    /**
     * @param null $version_id
     * @return bool|BU_Version
     */
    protected function getVersion($version_id = null)
    {
        if (is_null($version_id)) {
            $version_id = $this->getVersionId();
        }
        $version = new BU_Version();
        $version->get($version_id);
        return is_object($version->original) && is_object($version->post) ? $version : false;
    }
}
