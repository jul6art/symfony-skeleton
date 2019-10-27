<?php

namespace App\EntityListener;

use App\Entity\Functionality;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserEntityListener.
 */
class UserEntityListener extends AbstractEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.added', [], 'notification'));
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws NonUniqueResultException
     */
    public function preRemove(User $user, LifecycleEventArgs $event): void
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is active
        //
        if (!$this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.user.deleted', [], 'notification'));
        }
    }
}
