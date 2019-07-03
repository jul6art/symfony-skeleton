<?php

namespace App\EventSubscriber;

use App\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MailSubscriber.
 */
class MailSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::ADDED => 'onUserAdded',
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function onUserAdded(UserEvent $event): void
    {
    	$password = $event->find('password');
        // send mail to user with password
    }
}