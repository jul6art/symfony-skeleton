<?php

namespace App\EventSubscriber;

use App\Event\TestEvent;
use App\Manager\AuditManagerTrait;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestSubscriber.
 */
class TestSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
	use AuditManagerTrait;

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
	 * @param TestEvent $event
	 *
	 * @throws DBALException
	 * @throws MappingException
	 */
    public function onTestAdded(TestEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.added', [], 'notification'));

	    $this->getAuditManager()->audit('test01', $event->getTest(), [
		    'id' => $event->getTest()->getId(),
		    'planet' => 'Mars',
	    ]);
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
