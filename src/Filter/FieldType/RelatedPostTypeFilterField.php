<?php

namespace DevAnime\SatoruGojo\Filter\FieldType;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;
use DevAnime\SatoruGojo\Filter\Values\FilterableItem;
use RuntimeException;

class RelatedPostTypeFilterField extends FilterFieldPost
{
    const FILTER_TYPE = 'related_post_type';

    public function getFieldDataFromPost(PostBase $Post): FilterableCollection
    {
        if (!$post_type = $this->getConfigValue(static::FILTER_TYPE)) {
            throw new RuntimeException('Filter Field does not have an assigned post type');
        }
        $field_name = "{$post_type}_id";
        $related_post_ids = $Post->{$field_name};
        if (!is_array($related_post_ids)) {
            $related_post_ids = [$related_post_ids];
        }

        $items = [];
        foreach (array_filter($related_post_ids) as $related_post_id) {
            $related_post = get_post($related_post_id);
            if ($related_post) {
                $items[] = new FilterableItem($related_post->ID, get_the_title($related_post->ID));
            }
        }
        return new FilterableCollection($items);
    }

    public function getFieldConfigFromData(FilterableCollection $Collection)
    {
        return ['options' => $Collection->getAllSortedByLabel()];
    }

}