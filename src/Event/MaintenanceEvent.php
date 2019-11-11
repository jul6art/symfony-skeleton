<?php

namespace App\Event;

use App\Entity\Maintenance;

/**
 * Class MaintenanceEvent.
 */
class MaintenanceEvent extends AbstractEvent
{
    public const ADDED = 'event.maintenance.added';
    public const EDITED = 'event.maintenance.edited';
    public const DELETED = 'event.maintenance.deleted';

    /**
     * @var Maintenance
     */
    private $maintenance;

    /**
     * MaintenanceEvent constructor.
     *
     * @param Maintenance $maintenance
     */
    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
        parent::__construct();
    }

    /**
     * @return Maintenance
     */
    public function getMaintenance(): Maintenance
    {
        return $this->maintenance;
    }

    /**
     * @param Maintenance $maintenance
     *
     * @return self
     */
    public function setMaintenance(Maintenance $maintenance): self
    {
        $this->maintenance = $maintenance;

        return $this;
    }
}
