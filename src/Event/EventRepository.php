<?php

namespace DevAnime\SatoruGojo\Event;

use DevAnime\Repositories\PostRepository;

/**
 * Class EventRepository
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class EventRepository extends PostRepository
{
    protected $model_class = EventPost::class;

    /**
     * @param int $posts_per_page
     * @return array
     */
    public function findFuture(int $posts_per_page = -1)
    {
        return $this->find(compact('posts_per_page'));
    }
}
