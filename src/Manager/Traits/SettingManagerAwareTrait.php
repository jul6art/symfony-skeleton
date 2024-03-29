<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\SettingManager;

/**
 * Trait SettingManagerAwareTrait.
 */
trait SettingManagerAwareTrait
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
