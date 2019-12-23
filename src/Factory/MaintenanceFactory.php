<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\Maintenance;
use App\Factory\Interfaces\FactoryInterface;

/**
 * Class MaintenanceFactory
 * @package App\Factory
 */
final class MaintenanceFactory implements FactoryInterface
{
    /**
     * @param array $context
     * @return Maintenance|mixed
     */
    public static function create(array $context = [])
    {
        return new Maintenance();
    }
}
