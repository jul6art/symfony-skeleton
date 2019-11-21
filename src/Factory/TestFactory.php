<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:25.
 */

namespace App\Factory;

use App\Entity\Test;

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
