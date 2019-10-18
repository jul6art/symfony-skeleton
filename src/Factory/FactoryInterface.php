<?php

namespace App\Factory;

/**
 * Interface FactoryInterface
 * @package App\Factory
 */
interface FactoryInterface
{
    /**
     * @return mixed
     */
    public static function create();
}