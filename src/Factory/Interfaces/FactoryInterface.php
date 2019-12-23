<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory\Interfaces;

/**
 * Interface FactoryInterface.
 */
interface FactoryInterface
{
    /**
     * @param array $context
     * @return mixed
     */
    public static function create(array $context = []);
}
