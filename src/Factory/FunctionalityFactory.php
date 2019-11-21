<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:25.
 */

namespace App\Factory;

use App\Entity\Functionality;

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
