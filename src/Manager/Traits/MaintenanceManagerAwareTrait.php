<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\MaintenanceManager;

/**
 * Trait MaintenanceManagerAwareTrait.
 */
trait MaintenanceManagerAwareTrait
{
    /**
     * @var MaintenanceManager
     */
    protected $maintenanceManager;

    /**
     * @return MaintenanceManager
     */
    public function getMaintenanceManager(): MaintenanceManager
    {
        return $this->maintenanceManager;
    }

    /**
     * @param MaintenanceManager $maintenanceManager
     *
     * @required
     */
    public function setMaintenanceManager(MaintenanceManager $maintenanceManager): void
    {
        $this->maintenanceManager = $maintenanceManager;
    }
}
