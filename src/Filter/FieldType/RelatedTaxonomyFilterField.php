<?php

namespace DevAnime\SatoruGojo\Filter\FieldType;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;
use DevAnime\SatoruGojo\Filter\Values\FilterableItem;
use RuntimeException;

class RelatedTaxonomyFilterField extends FilterFieldPost
{
    const FILTER_TYPE = 'related_taxonomy';

    public function getFieldDataFromPost(PostBase $Post): FilterableCollection
    {
        if (!$taxonomy = $this->getConfigValue(static::FILTER_TYPE)) {
            throw new RuntimeException('Filter Field does not have an assigned taxonomy');
        }
        $items = [];
        foreach ($Post->terms($taxonomy)  as $term) {
            $parent = null;
            if ($term->parent) {
                $parent_term = get_term($term->parent, $taxonomy);
                $items[$parent_term->term_id] = $parent = new FilterableItem($parent_term->term_id, $parent_term->name);
            }
            $items[$term->term_id] = new FilterableItem($term->term_id, $term->name, $parent);
        }
        $items = apply_filters('devanime/satoru-gojo/filters/related-taxonomy/field-data', array_values($items), $taxonomy);
        return new FilterableCollection($items);
    }

    public function getFieldConfigFromData(FilterableCollection $Collection)
    {
        if (!$this->isSplitDisplay()) {
            return ['options' => $Collection->getAllSortedByLabel()];
        }
        $options = $sub_options = [];
        foreach ($Collection->getAllSortedByLabel() as $item) { /* @var FilterableItem $item */
            if ($item->getParent()) {
                $parent = $item->getParent();
                $options[$parent->getValue()] = $parent;
                $sub_options[$parent->getValue()][] = $item;
            } else {
                $options[$item->getValue()] = $item;
            }
        }
        return [
            'options' => (new FilterableCollection($options))->getAllSortedByLabel(),
            'sub_options' => $sub_options
        ];
    }


}
