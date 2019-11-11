<?php

namespace App\EntityListener;

use App\Entity\Maintenance;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class MaintenanceEntityListener.
 */
class MaintenanceEntityListener extends AbstractEntityListener
{
    /**
     * @param Maintenance        $maintenance
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Maintenance $maintenance, LifecycleEventArgs $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.maintenance.edited', [], 'notification'));
    }
}
