<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

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
