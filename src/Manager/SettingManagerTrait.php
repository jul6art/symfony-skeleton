<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait SettingManagerTrait.
 */
trait SettingManagerTrait
{
    /**
     * @var SettingManager
     */
    protected $settingManager;

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
     * @param SettingManager $settingManager
     *
     * @required
     */
    public function setSettingManager(SettingManager $settingManager): void
    {
        $this->settingManager = $settingManager;
    }
}
