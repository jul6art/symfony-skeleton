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
 * Trait GroupManagerTrait.
 */
trait GroupManagerTrait
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
