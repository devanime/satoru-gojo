<?php

namespace DevAnime\SatoruGojo\RelatedContent;
use DevAnime\Models\PostCollection;

/**
 * Class RelatedContentCollection
 * @package DevAnime\Produers\RelatedContent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class RelatedContentCollection extends PostCollection
{
    protected static $object_class_name = RelatedContent::class;
    protected $max_count = 0;

    public function __construct(array $items = [], $max_count = 0)
    {
        $this->max_count = (int) $max_count;
        parent::__construct($items);
    }

    public function add($item)
    {
        return $this->isFull() ? $this : parent::add($item);
    }


    protected function getObjectHash($item)
    {
        return md5($item->getPostId());
    }

    /**
     * @return int
     */
    public function getMaxCount(): int
    {
        return $this->max_count;
    }

    /**
     * @return bool
     */
    public function isFull()
    {
        return $this->max_count && $this->count() == $this->max_count;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }
}
