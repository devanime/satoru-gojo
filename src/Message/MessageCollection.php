<?php

namespace DevAnime\SatoruGojo\Message;

use DevAnime\Models\ObjectCollection;

/**
 * class MessageCollection
 *
 * @package DevAnime\SatoruGojo\Message
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class MessageCollection extends ObjectCollection
{
    protected static $object_class_name = Message::class;

    protected function getObjectHash($item)
    {
        return md5(serialize($item));
    }

    public static function createFromGlobalAssignments()
    {
        $global_message_assignments = get_field('global_message_assignments', 'option') ?: [];
        return new static(array_map(function($message) {
            return Message::createFromAssignment($message);
        }, $global_message_assignments));
    }

    /**
     * Only return global Modal messages and modal messages
     * that only appear for that particular page
     *
     * @param $id
     * @return array
     */
    public function getModalMessages($id = '')
    {
        if ($this->isEmpty()) {
            return [];
        }
        if (empty($id)) {
            $id = get_the_ID();
        }
        return array_filter($this->items, function($Message) use ($id) {
            /* @var Message $Message */
            return $Message->isGlobalModal() || $Message->getModalLocation() == $id;
        });
    }

    public function getConfigFromAllModalMessages()
    {
        if ($this->isEmpty()) {
            return [];
        }
        return $this->getConfigFromMessages($this->getModalMessages());
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getConfigFromDeprecatedMessageFields()
    {
        if ($this->isEmpty()) {
            return [];
        }
        return $this->getConfigFromMessages($this->getAll());
    }

    public function getConfigFromMessages($messages) {
        return array_values(array_map(function($Message) { /* @var Message $Message */
            $config = [
                'id' => $Message->getId(),
                'modalID' => $Message->getModalId(),
                'trigger' => $Message->getModalTrigger(),
                'delay' => $Message->getModalTriggerDelay(),
                'scrollDistance' => $Message->getModalTriggerDistance(),
                'start' => $Message->getStart()->format(DATE_ATOM),
                'end' => $Message->getEnd()->format(DATE_ATOM)
            ];
            if ($Message->getExpiry()) {
                $config['expiry'] = $Message->getExpiry();
            }
            return $config;
        }, $messages));
    }
}
