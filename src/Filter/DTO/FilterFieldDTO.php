<?php

namespace DevAnime\SatoruGojo\Filter\DTO;

use DevAnime\Models\DTO;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;
use DevAnime\SatoruGojo\Filter\Group\FilterGroupPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;

class FilterFieldDTO extends DTO
{
    protected $name;
    protected $group;
    protected $type;
    protected $input;
    protected $label;
    protected $all_results_label;
    protected $config;

    public function __construct(FilterFieldPost $FilterFieldPost, string $group, string $label, FilterableCollection $items = null)
    {
        $this->name = $FilterFieldPost->post()->post_name;
        $this->group = $group;
        $this->type = $FilterFieldPost->type;
        $this->input = $FilterFieldPost->display;
        $this->label = $label;
        $this->all_results_label = $FilterFieldPost->all_results_label ?: false;
        $this->config = array_filter($FilterFieldPost->getFieldConfigFromData($items ?: new FilterableCollection()));
    }
}
