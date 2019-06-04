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
class SettingFactory
{
    /**
     * @return Setting
     */
    public static function create(): Setting
    {
        return new Setting();
    }
}
