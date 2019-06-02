<?php

namespace App\EventSubscriber;

use App\Entity\Functionality;
use App\Event\TestEvent;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestSubscriber.
 */
class TestSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    use FunctionalityManagerTrait;

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
     * @param TestEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onTestDeleted(TestEvent $event)
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if fucc is actived
        //
        if (!$this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.test.deleted', [], 'notification'));
        }
    }
}
