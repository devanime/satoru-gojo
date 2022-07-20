<?php

namespace DevAnime\SatoruGojo\Filter\Values;

use DevAnime\Models\ObjectCollection;

class FilterableCollection extends ObjectCollection implements \JsonSerializable
{
    protected static $object_class_name = FilterableItem::class;

    protected function getObjectHash($item)
    {
        return md5($item->getHash());
    }

    public function jsonSerialize()
    {
        return $this->getAll();
    }

    public function getAllSortedByLabel()
    {
        $items = $this->getAll();
        usort($items, function(FilterableItem $a, FilterableItem $b) {
            return $a->getLabel() <=> $b->getLabel();
        });
        return $items;
    }

    public function getAllSortedByValue()
    {
        $items = $this->getAll();
        usort($items, function(FilterableItem $a, FilterableItem $b) {
            return $a->getValue() <=> $b->getValue();
        });
        return $items;
    }
}
