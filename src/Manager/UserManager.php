<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09
 */

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager {

	/**
	 * @var string
	 */
	private $default_theme;

	/**
	 * @var string
	 */
	private $locale;

	/**
	 * @var GroupManager
	 */
	private $groupManager;

	/**
	 * User constructor.
	 *
	 * @param string $default_theme
	 * @param string $locale
	 */
	public function __construct(string $default_theme, string $locale, GroupManager $groupManager)
	{
		$this->default_theme = $default_theme;
		$this->locale = $locale;
		$this->groupManager = $groupManager;
	}

	/**
	 * @return User
	 * @throws NonUniqueResultException
	 */
	public function create()
	{
		return (new User())
			->setEnabled(true)
			->addGroup($this->groupManager->findOneByName('user'))
			->setLocale($this->locale)
			->setTheme($this->default_theme);
	}

	/**
	 * @return User
	 * @throws NonUniqueResultException
	 */
	public function createAdmin()
	{
		return $this
			->create()
			->removeGroup($this->groupManager->findOneByName('user'))
			->addGroup($this->groupManager->findOneByName('admin'));
	}
}