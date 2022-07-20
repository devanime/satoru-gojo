<?php

namespace DevAnime\SatoruGojo;
use DevAnime\SatoruGojo\Message\Message;
use DevAnime\SatoruGojo\Message\MessageCollection;
use DevAnime\SatoruGojo\Support\Producer;
use DevAnime\Estarossa\Message\MessageModalView;
use DevAnime\Estarossa\Message\MessageView;
use DevAnime\View\Element;

/**
 * Class MessageProducer
 * @package DevAnime\SatoruGojo
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class MessageProducer extends Producer
{
    const COMPONENT = 'message';

    public function __construct()
    {
        parent::__construct();
        add_action('wp_footer', function() {
            $MessageCollection = MessageCollection::createFromGlobalAssignments();
            if ($MessageCollection instanceof MessageCollection && !$MessageCollection->isEmpty()) {
                echo Element::create('script', 'window.messageModalData = ' . json_encode($MessageCollection->getConfigFromAllModalMessages()),
                    ['type' => 'text/javascript']);
            }
        });
    }
}
