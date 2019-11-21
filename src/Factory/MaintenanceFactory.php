<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:25.
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
