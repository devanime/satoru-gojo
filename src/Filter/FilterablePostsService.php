<?php


namespace DevAnime\SatoruGojo\Filter;


use DevAnime\Models\PostCollection;
use DevAnime\SatoruGojo\Filter\DTO\FilterablePostDTO;
use DevAnime\SatoruGojo\Filter\DTO\FilterablePostsDTO;
use DevAnime\SatoruGojo\Filter\DTO\FilterFieldDTO;
use DevAnime\SatoruGojo\Filter\DTO\FilterGroupDTO;
use DevAnime\SatoruGojo\Filter\Group\FilterGroupPost;
use DevAnime\SatoruGojo\Filter\Values\FilterableItem;
use DevAnime\SatoruGojo\Filter\Values\FilterableCollection;

class FilterablePostsService
{
    protected $FilterGroup;
    protected $PostCollection;
    /**
     * @var FilterableCollection[]
     */
    protected $filterable_data_by_field = [];
    /**
     * @var FilterableCollection[]
     */
    protected $filterable_data_by_post = [];

    public function __construct(FilterGroupPost $FilterGroup, PostCollection $PostCollection)
    {
        $this->FilterGroup = $FilterGroup;
        $this->PostCollection = $PostCollection;
    }

    public function getFilterGroupName()
    {
        return $this->FilterGroup->post()->post_name;
    }

    public function getFilterGroupInputData(): FilterGroupDTO
    {
        if (empty($this->filterable_data_by_field)) {
            $this->setFilterableData();
        }
        $DTOs = [];
        foreach ($this->FilterGroup->fields as $label => $FilterField) {
            $options = [];
            $field_data = $this->filterable_data_by_field[$FilterField->ID] ?? [];
            foreach ($field_data as $collection) { /* @var FilterableCollection $collection */
                foreach ($collection as $item) { /* @var FilterableItem $item */
                    if ($item->getLabel()) {
                        $options[$item->getValue()] = $item;
                    }
                }
            }
            $DTOs[] = new FilterFieldDTO($FilterField, $this->getFilterGroupName(), $label, new FilterableCollection($options));
        }
        return new FilterGroupDTO($DTOs);
    }

    public function getFilterGroupConfigData(): FilterGroupDTO
    {
        if (empty($this->filterable_data_by_field)) {
            $this->setFilterableData();
        }
        $DTOs = [];
        foreach ($this->FilterGroup->fields as $label => $FilterField) {
            $DTOs[] = new FilterFieldDTO($FilterField, $this->getFilterGroupName(), $label);
        }
        return new FilterGroupDTO($DTOs);
    }

    public function getFilterablePostsData(): FilterablePostsDTO
    {
        if (empty($this->filterable_data_by_field)) {
            $this->setFilterableData();
        }
        $DTOs = [];
        foreach ($this->PostCollection as $Post) {
            $field_data = $this->filterable_data_by_post[$Post->ID] ?? [];
            $matches = [];
            foreach ($field_data as $key => $collection) { /* @var FilterableCollection $collection */
                $matches[$key] = $collection->mapMethod('getValue');
            }
            $DTOs[] = new FilterablePostDTO($Post, $matches);
        }
        return new FilterablePostsDTO($DTOs);
    }

    protected function setFilterableData()
    {
        foreach ($this->FilterGroup->fields as $FilterField) {
            foreach ($this->PostCollection as $Post) {
                $data = $FilterField->getFieldDataFromPost($Post);
                $this->filterable_data_by_field[$FilterField->ID][$Post->ID] = $data;
                $this->filterable_data_by_post[$Post->ID][$FilterField->name] = $data;
            }
        }
    }



}