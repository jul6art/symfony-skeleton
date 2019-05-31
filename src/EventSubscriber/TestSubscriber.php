<?php

namespace App\EventSubscriber;

use App\Event\TestEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestSubscriber.
 */
class TestSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TestEvent::ADDED => 'onTestAdded',
            TestEvent::EDITED => 'onTestEdited',
            TestEvent::DELETED => 'onTestDeleted',
        ];
    }

    /**
     * @param Event $event
     */
    public function onTestAdded(TestEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.added', [], 'notification'));
    }

    /**
     * @param Event $event
     */
    public function onTestEdited(TestEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.edited', [], 'notification'));
    }

    /**
     * @param Event $event
     */
    public function onTestDeleted(TestEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.deleted', [], 'notification'));
    }
}
