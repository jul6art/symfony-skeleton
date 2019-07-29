<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\TestTrait;
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
	use TestTrait;

	/**
	 * @var Generator
	 */
	private $faker;

	/**
	 * UserControllerTest constructor.
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
	 * User must be logged
	 */
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/user/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController index Action
	 *
	 * User has bad Roles
	 */
	public function testIndex02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/');

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController add Action
	 *
	 * User must be logged
	 */
	public function testAdd()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/user/add');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController add Action
	 *
	 * User has bad Roles
	 */
	public function testAdd02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/add');

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * User must be logged
	 */
	public function testShow()
	{
		$client = static::createClient();

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/view/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * User has bad Roles
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

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController show Action
	 *
	 * Not found
	 */
	public function testShow04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/view/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController edit Action
	 *
	 * User must be logged
	 */
	public function testEdit()
	{
		$client = static::createClient();

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/user/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController edit Action
	 *
	 * User has bad Roles
	 */
	public function testEdit02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/user/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController edit Action
	 *
	 * Successfull
	 */
	public function testEdit03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($tests)->getId();

		$crawler = $client->request('GET', "/admin/user/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController edit Action
	 *
	 * Not found
	 */
	public function testEdit04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$crawler = $client->request('GET', '/admin/user/edit/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * User must be logged
	 */
	public function testDelete()
	{
		$client = static::createClient();

		$users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
		$id = end($users)->getId();

		$client->request('GET', "/admin/user/delete/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * User has bad Roles
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

		$this->save('result.html', $client->getResponse()->getContent());

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

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\UserController delete Action
	 *
	 * Not found
	 */
	public function testDelete04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/user/delete/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}
}