<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25.
 */

namespace App\Factory;

use App\Entity\Group;
use App\Entity\User;
use App\Manager\GroupManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserFactory.
 */
class UserFactory
{
	/**
	 * @param GroupManager $groupManager
	 * @param string       $locale
	 * @param string       $defaultTheme
	 *
	 * @return User
	 *
	 * @throws NonUniqueResultException
	 */
	public static function create(GroupManager $groupManager, string $locale, string $defaultTheme): User
	{
		$user = (new User())
			->addGroup($groupManager->findOneByName(Group::GROUP_NAME_USER))
			->setLocale($locale)
			->setTheme($defaultTheme);

		return $user;
	}

	/**
	 * @param GroupManager $groupManager
	 * @param string       $locale
	 * @param string       $defaultTheme
	 *
	 * @return User
	 *
	 * @throws NonUniqueResultException
	 */
	public static function createAdmin(GroupManager $groupManager, string $locale, string $defaultTheme): User
	{
		return (new User())
			->addGroup($groupManager->findOneByName(Group::GROUP_NAME_ADMIN))
			->setLocale($locale)
			->setTheme($defaultTheme);
	}
}