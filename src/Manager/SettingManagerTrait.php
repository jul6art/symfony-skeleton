<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
