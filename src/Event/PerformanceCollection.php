<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Models\ObjectCollection;

/**
 * Class PerformanceCollection
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class PerformanceCollection extends ObjectCollection
{
    protected static $object_class_name = Performance::class;

    public function __construct(array $items = [])
    {
        usort($items, function (Performance $a, Performance $b) {
            return $a->getDateTime()->getTimeStamp() - $b->getDateTime()->getTimeStamp();
        });
        parent::__construct($items);
    }

    /**
     * @param array $rows
     * @return PerformanceCollection
     */
    public static function createFromPerformanceRows(array $rows)
    {
        return new static(array_map([static::$object_class_name, 'createFromRow'], $rows));
    }

    /**
     * @param Performance $item
     * @return string
     */
    protected function getObjectHash($item)
    {
        return md5($item->getDateTime());
    }

    /**
     * Find all future performances
     *
     * @return ObjectCollection
     */
    public function findFuture()
    {
        return $this()->filterMethod('isFuture');
    }

    /**
     * Find all future performances that are available (and not sold out)
     * @return ObjectCollection
     */
    public function findFutureAvailable()
    {
        return $this->findFuture()->filterMethod('isAvailable');
    }

    /**
     * Find all future performances that are sold out
     * @return ObjectCollection
     */
    public function findFutureSoldOut()
    {
        return $this->findFuture()->filterMethod('isSoldOut');
    }

    /**
     * Find the next performance
     *
     * @return Performance|null
     */
    public function findNext()
    {
        $Collection = $this->findFuture();
        $performances = $Collection->getAll();
        return $Collection->count() ? reset($performances) : null;
    }

    /**
     * Find the first performance.
     *
     * @return Performance|null
     */
    public function findFirst()
    {
        $performances = $this->getAll();
        return count($performances) ? reset($performances) : null;
    }

    /**
     * Find the last performance.
     *
     * @return Performance|null
     */
    public function findLast()
    {
        $performances = $this->getAll();
        return count($performances) ? end($performances) : null;
    }
}
