<?php

namespace DevAnime\SatoruGojo\Event;
use DevAnime\Controller\RestController;
use WP_REST_Request;

/**
 * Class EventRestController
 * @package DevAnime\SatoruGojo\Event
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
final class EventRestController extends RestController
{
    protected $namespace = 'events';

    public function registerRoutes()
    {
        $this->addReadRoute('/future', 'future');
    }

    public function future(WP_REST_Request $request)
    {
        $EventRepository = new EventRepository();
        return new EventDTOCollection($EventRepository->findAll());
    }
}
