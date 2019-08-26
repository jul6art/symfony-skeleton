<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25
 */

namespace App\Tests\Factory;

use App\Entity\Setting;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserFactoryTest
 * @package App\Tests\Factory
 */
class UserFactoryTest extends WebTestCase
{
	/**
	 * UserFactoryTest constructor.
	 *
	 * @param null|string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( ?string $name = null, array $data = [], string $dataName = '' )
	{
		parent::__construct( $name, $data, $dataName );
	}

	/**
	 * Test App\\Factory\\UserFactory create Action
	 *
	 * @throws NonUniqueResultException
	 */
	public function testCreate()
	{
		$client = static::createClient();

		$groupManager = $client->getContainer()->get('App\Manager\GroupManager');

		$locale = $client->getContainer()->getParameter('locale');

		$theme = Setting::SETTING_DEFAULT_THEME_VALUE;

		$user = UserFactory::create($groupManager, $locale, $theme);

		$this->assertInstanceOf(User::class, $user);

		$this->assertEquals(false, $user->isAdmin());
	}

	/**
	 * Test App\\Factory\\UserFactory createAdmin Action
	 *
	 * @throws NonUniqueResultException
	 */
	public function testCreateAdmin()
	{
		$client = static::createClient();

		$groupManager = $client->getContainer()->get('App\Manager\GroupManager');

		$locale = $client->getContainer()->getParameter('locale');

		$theme = Setting::SETTING_DEFAULT_THEME_VALUE;

		$user = UserFactory::createAdmin($groupManager, $locale, $theme);

		$this->assertInstanceOf(User::class, $user);

		$this->assertEquals(true, $user->isAdmin());
	}
}