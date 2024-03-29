<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventListener;

use App\Entity\User;
use App\Event\UserEvent;
use App\Message\NotifyOnUserAddedMessage;
use App\Message\NotifyOnRegistrationMessage;
use App\Traits\MessageBusAwareTrait;
use FOS\UserBundle\Event\FormEvent;
use Throwable;

/**
 * Class NotificationListener.
 */
class NotificationListener
{
    use MessageBusAwareTrait;

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        $user = $event->getForm()->getData();

        if ($user instanceof User) {
            $this->bus->dispatch(new NotifyOnRegistrationMessage(
                $user->getId(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getUsername(),
                $user->getEmail()
            ));
        }
    }

    /**
     * @param UserEvent $event
     *
     * @throws Throwable
     */
    public function onUserAdded(UserEvent $event): void
    {
        $user = $event->getUser();

        $this->bus->dispatch(new NotifyOnUserAddedMessage(
            $user->getId(),
            $event->find('createdBy')->getId(),
            $user->getFirstname(),
            $user->getLastname(),
            $user->getUsername(),
            $user->getEmail(),
            $event->find('password')
        ));
    }
}
