<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41
 */

namespace App\Manager;

/**
 * Trait GroupManagerTrait
 * @package App\Manager
 */
trait GroupManagerTrait {
	/**
	 * @var GroupManager
	 */
	private $groupManager;

	/**
	 * GroupManagerTrait constructor.
	 *
	 * @param GroupManager $groupManager
	 */
	public function __construct(GroupManager $groupManager)
	{
		$this->groupManager = $groupManager;
	}

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
	 * @return GroupManagerTrait
	 */
	public function setGroupManager(GroupManager $groupManager): GroupManagerTrait
	{
		$this->groupManager = $groupManager;

		return $this;
	}
}