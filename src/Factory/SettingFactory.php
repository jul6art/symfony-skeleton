<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\Setting;
use App\Factory\Interfaces\FactoryInterface;

/**
 * Class SettingFactory
 * @package App\Factory
 */
final class SettingFactory implements FactoryInterface
{
    /**
     * @param array $context
     * @return Setting|mixed
     */
    public static function create(array $context = [])
    {
        return new Setting();
    }
}
