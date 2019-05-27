<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25
 */

namespace App\Factory;

use App\Entity\User;
use App\Manager\GroupManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserFactory
 * @package App\Factory
 */
class UserFactory {
	/**
	 * @param GroupManager $groupManager
	 * @param string $locale
	 * @param string $default_theme
	 *
	 * @return User
	 * @throws NonUniqueResultException
	 */
	public static function create(GroupManager $groupManager, string $locale, string $default_theme): User
	{
		return (new User())
			->setEnabled(true)
			->addGroup($groupManager->findOneByName('user'))
			->setLocale($locale)
			->setTheme($default_theme);
	}

	/**
	 * @param GroupManager $groupManager
	 * @param string $locale
	 * @param string $default_theme
	 *
	 * @return User
	 * @throws NonUniqueResultException
	 */
	public static function createAdmin(GroupManager $groupManager, string $locale, string $default_theme): User
	{
		return (new User())
			->setEnabled(true)
			->addGroup($groupManager->findOneByName('admin'))
			->setLocale($locale)
			->setTheme($default_theme);
	}
}