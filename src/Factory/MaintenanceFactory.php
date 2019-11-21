<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\Maintenance;

/**
 * Class MaintenanceFactory.
 */
class MaintenanceFactory implements FactoryInterface
{
    /**
     * @return Maintenance|mixed
     */
    public static function create()
    {
        return new Maintenance();
    }
}
