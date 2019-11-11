<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait MaintenanceManagerTrait.
 */
trait MaintenanceManagerTrait
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
