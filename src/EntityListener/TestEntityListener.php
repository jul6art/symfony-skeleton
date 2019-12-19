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
use App\Entity\Test;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class TestEntityListener.
 */
class TestEntityListener extends AbstractEntityListener
{
    /**
     * @param Test               $test
     * @param LifecycleEventArgs $event
     */
    public function postPersist(Test $test, LifecycleEventArgs $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.added', [], 'notification'));
    }

    /**
     * @param Test               $test
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Test $test, LifecycleEventArgs $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.edited', [], 'notification'));
    }

    /**
     * @param Test               $test
     * @param LifecycleEventArgs $event
     *
     * @throws NonUniqueResultException
     */
    public function preRemove(Test $test, LifecycleEventArgs $event): void
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is active
        //
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.test.deleted', [], 'notification'));
        }
    }
}
