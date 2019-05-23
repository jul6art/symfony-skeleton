<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41
 */

namespace App\Manager;

/**
 * Trait UserManagerTrait
 * @package App\Manager
 */
trait UserManagerTrait {
	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * UserManagerTrait constructor.
	 *
	 * @param UserManager $userManager
	 */
	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	/**
	 * @return UserManager
	 */
	public function getUserManager(): UserManager
	{
		return $this->userManager;
	}

	/**
	 * @param UserManager $userManager
	 *
	 * @return UserManagerTrait
	 */
	public function setUserManager(UserManager $userManager): UserManagerTrait
	{
		$this->userManager = $userManager;

		return $this;
	}
}