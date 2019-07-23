<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 * @package App\Tests\Controller
 */
class UserControllerTest extends WebTestCase
{
	/**
	 * @var Generator
	 */
	private $faker;

	/**
	 * TestControllerTest constructor.
	 *
	 * @param null|string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( ?string $name = null, array $data = [], string $dataName = '' )
	{
		parent::__construct( $name, $data, $dataName );

		$this->faker = Factory::create();
	}

	/**
	 * Test App\\Controller\\UserController index Action
	 *
	 * Test must be logged
	 */
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/user/');

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController index Action
	 *
	 * Test user has bad Roles
	 */
	public function testIndex02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/');

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController index Action
	 *
	 * Successfull
	 */
	public function testIndex03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController add Action
	 *
	 * Test must be logged
	 */
	public function testAdd()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/user/add');

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController add Action
	 *
	 * Test user has bad Roles
	 */
	public function testAdd02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/add');

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController add Action
	 *
	 * Successfull
	 */
	public function testAdd03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$crawler = $client->request('GET', '/admin/user/add');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$form = $crawler->filter('button[type="submit"]')->form();

		$values = [
			'add_user[gender]'    => 'm',
			'add_user[username]'    => $this->faker->userName,
			'add_user[firstname]'    => $this->faker->firstName,
			'add_user[lastname]'    => $this->faker->lastName,
			'add_user[email]'    => $this->faker->email,
		];

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values,
			$form->getPhpFiles());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * Test must be logged
	 */
	public function testShow()
	{
		$client = static::createClient();

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/view/$id");

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * Test user has bad Roles
	 */
	public function testShow02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/view/$id");

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * Successfull
	 */
	public function testShow03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/view/$id");

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * Test must be logged
	 */
	public function testDelete()
	{
		$client = static::createClient();

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/delete/$id");

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * Test user has bad Roles
	 */
	public function testDelete02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/delete/$id");

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * Successfull
	 */
	public function testDelete03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/delete/$id");

		$client->followRedirect();

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}
}