<?php

/*
Plugin Name: Satoru  Gojo
Description: A collection of content management models
Version: 9999
License: GPL-2.0+
*/

use DevAnime\SatoruGojo;

define('SATORU_GOJO_PLUGIN_VER', '0.1');
define('SATORU_GOJO_PLUGIN_FILE', __FILE__);
define('SATORU_GOJO_PLUGIN_DIR', __DIR__);

if (! defined('USE_COMPOSER_AUTOLOADER') || ! USE_COMPOSER_AUTOLOADER) {
    require __DIR__ . '/vendor/autoload.php';
}

add_action('devanime/init', function() {
    add_theme_support('global-producer');
    add_action('after_setup_theme', function () {
        $producers = [
            SatoruGojo\GlobalProducer::class,
            SatoruGojo\TalentProducer::class,
            SatoruGojo\VideoProducer::class,
            SatoruGojo\MediaGalleryProducer::class,
            SatoruGojo\TourProducer::class,
            SatoruGojo\MessageProducer::class,
            SatoruGojo\EventProducer::class,
            SatoruGojo\FilterProducer::class,
            SatoruGojo\RelatedContentProducer::class,
            SatoruGojo\TicketsProducer::class,
            SatoruGojo\FAQProducer::class,
            SatoruGojo\PreviewProducer::class
        ];
        foreach ($producers as $producer_class) {
            $support_key = $producer_class::COMPONENT . '-producer';
            if (current_theme_supports($support_key)) {
                new $producer_class;
            }
        }
    }, 99);
});
