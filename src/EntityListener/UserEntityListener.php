<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EntityListener;

use App\Entity\Constants\FunctionalityName;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserEntityListener.
 */
class UserEntityListener extends AbstractEntityListener
{
    /**
     * @param User               $user
     * @param LifecycleEventArgs $event
     */
    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.added', [], 'notification'));
    }

    /**
     * @param User               $user
     * @param LifecycleEventArgs $event
     *
     * @throws NonUniqueResultException
     */
    public function preRemove(User $user, LifecycleEventArgs $event): void
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is active
        //
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.user.deleted', [], 'notification'));
        }
    }
}
