<?php

namespace DevAnime\SatoruGojo\Filter\Values;

class FilterableItem implements \JsonSerializable
{
    protected $value;
    protected $label;
    protected $parent_item;

    /**
     * FieldData constructor.
     * @param string|array $value
     * @param string $label
     * @param FilterableItem $parent_item
     */
    public function __construct($value, string $label = '', FilterableItem $parent_item = null)
    {
        if (is_array($value)) {
            array_walk($value, 'wp_strip_all_tags');
        } else {
            $value = wp_strip_all_tags($value);
        }
        $this->value = $value;
        $this->label = $label;
        $this->parent_item = $parent_item;
    }

    /**
     * @return string|array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return FilterableItem|null
     */
    public function getParent()
    {
        return $this->parent_item;
    }

    public function getHash()
    {
        return is_array($this->value) ? $this->value[0] : $this->value;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
