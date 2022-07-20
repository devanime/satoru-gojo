<?php

namespace DevAnime\SatoruGojo\Filter;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FieldType\DateFilterField;
use DevAnime\SatoruGojo\Filter\FieldType\KeywordFilterField;
use DevAnime\SatoruGojo\Filter\FieldType\RelatedPostTypeFilterField;
use DevAnime\SatoruGojo\Filter\FieldType\RelatedTaxonomyFilterField;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;
use InvalidArgumentException;

/**
 * Class FilterFieldPost
 * @package DevAnime\SatoruGojo\Filter
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 *
 * @property string $display
 * @property string $type
 * @property string $name
 * @property string $all_results_label
 *
 */
abstract class FilterFieldPost extends PostBase
{
    const POST_TYPE = 'filter-field';
    const META_KEY_FILTER_TYPE = 'filter_type';
    const META_KEY_LAYOUT = 'acf_fc_layout';
    const SPLIT_DISPLAY_KEY = 'split';
    const FILTER_TYPE = null;

    const TYPE_CLASS_MAP = [
        RelatedPostTypeFilterField::FILTER_TYPE => RelatedPostTypeFilterField::class,
        RelatedTaxonomyFilterField::FILTER_TYPE => RelatedTaxonomyFilterField::class,
        DateFilterField::FILTER_TYPE => DateFilterField::class,
        KeywordFilterField::FILTER_TYPE => KeywordFilterField::class
    ];

    public static function create($post = null)
    {
        $filter_type = static::getFilterType($post);
        $type_class_map = apply_filters('devanime/satoru-gojo/filter/class-map', static::TYPE_CLASS_MAP);
        if (!($filter_type && isset($type_class_map[$filter_type]))) {
            throw new InvalidArgumentException('Invalid post for subclass of FilterFieldPost');
        }
        $class_name = $type_class_map[$filter_type];
        return new $class_name($post);
    }

    public function isSplitDisplay()
    {
        return $this->display == static::SPLIT_DISPLAY_KEY;
    }

    public function getConfigValue($key, $array = false)
    {
        $config = $this->getFieldSetup();
        $value = $config[$key] ?? null;
        if ($array) {
            $value = array_filter((array) $value);
        }
        return $value;
    }

    protected static function getLayoutField($post_id)
    {
        $rows = get_field(static::META_KEY_FILTER_TYPE, $post_id);
        return $rows[0] ?? [];
    }

    protected static function getFilterType($post_id)
    {
        $layout = static::getLayoutField($post_id);
        return $layout[static::META_KEY_LAYOUT] ?? null;
    }

    protected function getLayout()
    {
        return static::getLayoutField($this->ID);
    }

    protected function get_name()
    {
        return $this->post()->post_name;
    }

    protected function get_type()
    {
        return static::getFilterType($this->ID);
    }

    protected function getFieldSetup()
    {
        if (empty($layout = $this->getLayout())) {
            return null;
        }
        unset($layout[static::META_KEY_LAYOUT]);
        return $layout;
    }

    /**
     * @param PostBase $Post
     * @return FilterableCollection
     */
    public abstract function getFieldDataFromPost(PostBase $Post): FilterableCollection;

    /**
     * @param FilterableCollection $Collection
     * @return array
     */
    public abstract function getFieldConfigFromData(FilterableCollection $Collection);
}
