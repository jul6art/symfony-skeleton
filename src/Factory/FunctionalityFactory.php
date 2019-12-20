<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\Functionality;
use App\Factory\Interfaces\FactoryInterface;

/**
 * Class FunctionalityFactory.
 */
class FunctionalityFactory implements FactoryInterface
{
    /**
     * @return Functionality|mixed
     */
    public static function create()
    {
        return new Functionality();
    }
}
