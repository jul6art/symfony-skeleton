<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25.
 */

namespace App\Factory;

use App\Entity\Setting;

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
