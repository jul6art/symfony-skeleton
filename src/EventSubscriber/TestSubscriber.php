<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\TestEvent;
use App\Manager\UserManagerTrait;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TestSubscriber
 * @package App\EventSubscriber
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