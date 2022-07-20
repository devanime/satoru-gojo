<?php

namespace DevAnime\SatoruGojo;

use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Support\Producer;

/**
 * Class FilterProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class FilterProducer extends Producer
{
    const COMPONENT = 'filter';

    public function __construct()
    {
        parent::__construct();
        add_filter('acf/load_field/key=field_5c8ba96dce8f5', [$this, 'loadPostTypes']);
        add_filter('acf/load_field/key=field_5c8ba9a1ce8f7', [$this, 'loadTaxonomies']);
        add_filter('acf/load_field/key=field_5ccb558527b0a', [$this, 'loadTaxonomies']);
    }

    public function loadPostTypes($field)
    {
        $choices = [];
        foreach (get_post_types(['public' => true], 'object') as $post_type) { /* @var \WP_Post_Type $post_type */
            $choices[$post_type->name] = $post_type->label;

        }
        $field['choices'] = $choices;
        return $field;
    }

    public function loadTaxonomies($field)
    {
        $choices = [];
        foreach (get_taxonomies(['public' => true], 'object') as $taxonomy) { /* @var \WP_Taxonomy $taxonomy */
            $choices[$taxonomy->name] = $taxonomy->label;

        }
        $field['choices'] = $choices;
        return $field;
    }
}
