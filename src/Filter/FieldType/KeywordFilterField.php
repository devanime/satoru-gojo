<?php

namespace DevAnime\SatoruGojo\Filter\FieldType;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;
use DevAnime\SatoruGojo\Filter\Values\FilterableItem;

class KeywordFilterField extends FilterFieldPost
{
    const FILTER_TYPE = 'keyword';

    public function getFieldDataFromPost(PostBase $Post): FilterableCollection
    {
        $items = [];
        foreach ($this->getConfigValue('metadata_search_keys', true) as $field_name) {
            $value = $Post->{$field_name};
            if ($value) {
                $items[] = new FilterableItem($value);
            }
        }
        foreach ($this->getConfigValue('post_search_keys', true) as $field_name) {
            $value = $field_name == 'post_excerpt' ?
                $Post->excerpt(0, true) : $Post->{$field_name};
            if ($value) {
                $items[] = new FilterableItem($value);
            }
            $normalized_value = $this->getNormalizedValue($value);

            if ($normalized_value != $value) {
                $items[] = new FilterableItem($normalized_value);
            }
        }
        foreach ($this->getConfigValue('taxonomy_search_keys', true) as $taxonomy_name) {
            $terms = $Post->terms($taxonomy_name);
            foreach ($terms as $term) {
                $items[] = new FilterableItem($term->name);
            }
        }
        return new FilterableCollection($items);
    }

    public function getFieldConfigFromData(FilterableCollection $Collection)
    {
        $min_characters = (int) $this->getConfigValue('min_characters');
        $placeholder = $this->getConfigValue('placeholder');
        return compact('min_characters', 'placeholder');
    }

    protected function get_display()
    {
        return 'text';
    }

    protected function getNormalizedValue($value)
    {
        return preg_replace('/[\W_]+/', ' ', remove_accents($value));
    }
}