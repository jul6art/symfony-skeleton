<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\Test;
use App\Factory\Interfaces\FactoryInterface;

/**
 * Class TestFactory.
 */
class TestFactory implements FactoryInterface
{
    /**
     * @return Test|mixed
     */
    public static function create()
    {
        return new Test();
    }
}
