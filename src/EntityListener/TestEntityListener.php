<?php

namespace App\EntityListener;

use App\Entity\Functionality;
use App\Entity\Test;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class TestEntityListener.
 */
class TestEntityListener extends AbstractEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(Test $test, LifecycleEventArgs $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.added', [], 'notification'));
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(Test $test, LifecycleEventArgs $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.test.edited', [], 'notification'));
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws NonUniqueResultException
     */
    public function preRemove(Test $test, LifecycleEventArgs $event)
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is active
        //
        if (!$this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.test.deleted', [], 'notification'));
        }
    }
}
