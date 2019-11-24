<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\UserEvent;
use App\Message\NotifyOnRegistrationMessage;
use App\Message\NotifyOnAddedMessage;
use App\Service\MailerServiceTrait;
use App\Traits\MessageBusTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailSubscriber.
 */
class MailSubscriber implements EventSubscriberInterface
{
    use MailerServiceTrait;
    use MessageBusTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            UserEvent::ADDED => 'onUserAdded',
        ];
    }

    /**
     * @param FormEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        $user = $event->getForm()->getData();
        if ($user instanceof User) {
            $this->bus->dispatch(new NotifyOnRegistrationMessage(
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onUserAdded(UserEvent $event): void
    {
        $user = $event->getUser();
        $password = $event->find('password');

        $this->bus->dispatch(new NotifyOnAddedMessage(
            $user->getFirstname(),
            $user->getLastname(),
            $user->getUsername(),
            $user->getEmail(),
            $password
        ));
    }
}
