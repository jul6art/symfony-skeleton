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
 * Class SettingFactory.
 */
class SettingFactory implements FactoryInterface
{
    /**
     * @return Setting|mixed
     */
    public static function create()
    {
        return new Setting();
    }
}
