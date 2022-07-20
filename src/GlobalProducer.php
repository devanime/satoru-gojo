<?php
/**
 * Class GlobalProducer
 * @package DevAnime\SatoruGojo
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo;

use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\Estarossa\Icon\IconView;
use DevAnime\Estarossa\ReadMore\ReadMoreView;
use DevAnime\View\AdminNotification;

class GlobalProducer extends Producer
{
    const COMPONENT = 'global';

    public function __construct()
    {
        parent::__construct();
        add_shortcode('svg-icon', [$this, 'svgShortcode']);
        add_shortcode('read-more', [$this, 'readMoreShortcode']);
        AdminNotification::init();
    }

    public function svgShortcode($atts, $content)
    {
        $atts = shortcode_atts(array(
            'icon' => '',
            'style' => '',
            'direction' => ''
        ), $atts, 'svg-icon');

        return IconView::create($atts['icon'], $atts['style'], $atts['direction']);
    }

    public function readMoreShortcode($atts, $content)
    {
        $atts = shortcode_atts([
            'delimiter-count' => 1,
            'aria-label' => ''
        ], $atts, 'read-more');

        return ReadMoreView::createFromText(apply_filters('the_content', $content), $atts['delimiter-count'], $atts['aria-label']);
    }
}
