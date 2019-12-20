<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\GroupManager;

/**
 * Trait GroupManagerAwareTrait.
 */
trait GroupManagerAwareTrait
{
    /**
     * @var GroupManager
     */
    protected $groupManager;

    /**
     * @return GroupManager
     */
    public function getGroupManager(): GroupManager
    {
        return $this->groupManager;
    }

    /**
     * @param GroupManager $groupManager
     *
     * @required
     */
    public function setGroupManager(GroupManager $groupManager): void
    {
        $this->groupManager = $groupManager;
    }
}
