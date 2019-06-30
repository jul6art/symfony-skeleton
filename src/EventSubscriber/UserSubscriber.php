<?php

namespace App\EventSubscriber;

use App\Entity\Functionality;
use App\Event\UserEvent;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubscriber.
 */
class UserSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    use FunctionalityManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvent::ADDED => 'onUserAdded',
            UserEvent::EDITED => 'onUserEdited',
            UserEvent::DELETED => 'onUserDeleted',
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function onUserAdded(UserEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.added', [], 'notification'));
    }

    /**
     * @param Event $event
     */
    public function onUserEdited(UserEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.edited', [], 'notification'));
    }

    /**
     * @param UserEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onUserDeleted(UserEvent $event)
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is actived
        //
        if (!$this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.user.deleted', [], 'notification'));
        }
    }
}
