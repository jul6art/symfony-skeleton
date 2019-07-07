<?php

namespace App\EventSubscriber;

use App\Event\UserEvent;
use App\Service\MailerServiceTrait;
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

        $this->mailerService->send($user->getEmail(), 'email/user/add/email.html.twig', [
        	'password' => $password,
        	'user' => $user,
        ]);
    }
}
