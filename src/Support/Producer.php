<?php

namespace DevAnime\SatoruGojo\Support;

use DevAnime\DevAnimeBase;
use DevAnime\Config;
use DevAnime\Models\PostFactory;

class Producer extends DevAnimeBase
{
    const COMPONENT = null;
    const API_PREFIX = 'api';

    protected static $_default_settings = [
        'ver' => SATORU_GOJO_PLUGIN_VER
    ];

    protected static $default_config_locations = [
        'config_files' => 'config.json',
        'acf_paths'    => 'acf'
    ];

    protected static $_file = SATORU_GOJO_PLUGIN_FILE;

    protected $model_classes = [];

    protected $additional_config_locations = [];

    public function __construct()
    {
        $settings = static::settings();
        $component_path = sprintf('%sconfig/%s/', $settings->base_dir, static::COMPONENT);
        $config_locations = array_merge(static::$default_config_locations, $this->additional_config_locations);
        $this->_config = new Config(array_map(function($location) use ($component_path) {
            return $component_path . $location;
        }, $config_locations));

        foreach ($this->model_classes as $model_class) {
            PostFactory::registerPostModel($model_class);
        }
    }
}
