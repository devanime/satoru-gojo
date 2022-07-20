<?php

namespace DevAnime\SatoruGojo\Filter\FieldType;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;
use DevAnime\SatoruGojo\Filter\Values\FilterableItem;
use DevAnime\Support\DateTime;
use DateInterval;

class DateFilterField extends FilterFieldPost
{
    const FILTER_TYPE = 'date';
    const VALUE_FORMAT = 'Y-m';
    const DEFAULT_LABEL_FORMAT = 'F - Y';

    public function getFieldDataFromPost(PostBase $Post): FilterableCollection
    {
        $search_key = $this->getConfigValue('search_key');
        if ($search_key == 'post_date') {
            return new FilterableCollection([$this->getDateItem($Post->publishedDate())]);
        }
        $items = [];
        if ($start_date = $this->getPostDateField($Post, 'meta_key_start')) {
            $items[] = $this->getDateItem($start_date);
        }
        if ($start_date && $end_date = $this->getPostDateField($Post, 'meta_key_end')) {
            $intermediate_month = new DateTime($start_date->format('Y-m'));
            $end_month = new DateTime($end_date->format('Y-m'));
            while ($intermediate_month < $end_month){
                $intermediate_month->add(new DateInterval('P1M'));
                $items[] = $this->getDateItem($intermediate_month);
            }
        }
        return new FilterableCollection($items);
    }

    public function getFieldConfigFromData(FilterableCollection $Collection)
    {
        return ['options' => $Collection->getAllSortedByValue()];
    }

    protected function getPostDateField(PostBase $Post, $name)
    {
        $field_name = $this->getConfigValue($name);
        $date = $Post->{$field_name};
        if (!$date) {
            return null;
        }
        if (!$date instanceof DateTime) {
            $date = new DateTime($date);
        }
        return $date;
    }

    protected function getDateItem(DateTime $date)
    {
        $format = $this->getConfigValue('format') ?: static::DEFAULT_LABEL_FORMAT;
        return new FilterableItem($date->format(static::VALUE_FORMAT), $date->format($format));
    }

}