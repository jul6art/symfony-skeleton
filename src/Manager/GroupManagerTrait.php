<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
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
