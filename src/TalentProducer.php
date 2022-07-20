<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\SatoruGojo\Talent\TalentPost;

/**
 * Class TalentProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TalentProducer extends Producer
{
    const COMPONENT = 'talent';
    protected $model_classes = [TalentPost::class];

    public function __construct()
    {
        parent::__construct();
        add_filter('acf/load_field/key=field_5ce589215981b', [$this, 'loadLayoutOptions']);
    }

    public function loadLayoutOptions($field)
    {
        $field['choices'] = apply_filters('satoru-gojo/talent/layout-options', $field['choices'] ?? []);
        return $field;
    }
}
