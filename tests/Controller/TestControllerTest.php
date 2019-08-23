<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Entity\Test;
use App\Entity\User;
use App\Tests\TestTrait;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestControllerTest
 * @package App\Tests\Controller
 */
class TestControllerTest extends WebTestCase
{
	use TestTrait;

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
	 * Test App\\Controller\\TestController index Action
	 *
	 * User must be logged
	 */
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/test/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController index Action
	 *
	 * User has bad Roles
	 */
	public function testIndex02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/test/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController index Action
	 *
	 * Successfull
	 */
	public function testIndex03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/test/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController add Action
	 *
	 * User must be logged
	 */
	public function testAdd()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/test/add');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController add Action
	 *
	 * User has bad Roles
	 */
	public function testAdd02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/test/add');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController add Action
	 *
	 * Invalid form
	 */
	public function testAdd03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$crawler = $client->request('GET', '/admin/test/add');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$this->assertEquals(1, $crawler->filter('[name="add_test[name]"]')->count());

		$this->assertEquals(1, $crawler->filter('[name="add_test[content]"]')->count());

		$form = $crawler->filter('form[name="add_test"] [type="submit"]')->form();
		$crawler = $client->submit($form, [
			'add_test[name]' => $this->faker->name,
		]);

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
	}

	/**
	 * Test App\\Controller\\TestController add Action
	 *
	 * Successfull
	 */
	public function testAdd04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$crawler = $client->request('GET', '/admin/test/add');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$this->assertEquals(1, $crawler->filter('[name="add_test[name]"]')->count());

		$this->assertEquals(1, $crawler->filter('[name="add_test[content]"]')->count());

		$form = $crawler->filter('form[name="add_test"] [type="submit"]')->form();
		$client->submit($form, [
			'add_test[name]' => $this->faker->name,
			'add_test[content]' => $this->faker->text,
		]);

		$crawler = $client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(0, $crawler->filter('.has-error')->count());
	}

	/**
	 * Test App\\Controller\\TestController show Action
	 *
	 * User must be logged
	 */
	public function testShow()
	{
		$client = static::createClient();

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/view/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController show Action
	 *
	 * User has bad Roles
	 */
	public function testShow02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/view/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController show Action
	 *
	 * Successfull
	 */
	public function testShow03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/view/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController edit Action
	 *
	 * User must be logged
	 */
	public function testEdit()
	{
		$client = static::createClient();

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController edit Action
	 *
	 * User has bad Roles
	 */
	public function testEdit02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController edit Action
	 *
	 * Invalid form
	 */
	public function testEdit03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$crawler = $client->request('GET', "/admin/test/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$this->assertEquals(1, $crawler->filter('[name="edit_test[name]"]')->count());

		$this->assertEquals(1, $crawler->filter('[name="edit_test[content]"]')->count());

		$form = $crawler->filter('form[name="edit_test"] [type="submit"]')->form();
		$crawler = $client->submit($form, [
			'edit_test[name]' => $this->faker->name,
			'edit_test[content]' => 'INVALID',
		]);

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
	}

	/**
	 * Test App\\Controller\\UserController edit Action
	 *
	 * Not found
	 */
	public function testEdit04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$crawler = $client->request('GET', '/admin/test/edit/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController edit Action
	 *
	 * Successfull
	 */
	public function testEdit05()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$crawler = $client->request('GET', "/admin/test/edit/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$this->assertEquals(1, $crawler->filter('[name="edit_test[name]"]')->count());

		$this->assertEquals(1, $crawler->filter('[name="edit_test[content]"]')->count());

		$form = $crawler->filter('form[name="edit_test"] [type="submit"]')->form();
		$client->submit($form, [
			'edit_test[name]' => $this->faker->name,
			'edit_test[content]' => $this->faker->text,
		]);

		$crawler = $client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(0, $crawler->filter('.has-error')->count());
	}

	/**
	 * Test App\\Controller\\TestController delete Action
	 *
	 * User must be logged
	 */
	public function testDelete()
	{
		$client = static::createClient();

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController delete Action
	 *
	 * User has bad Roles
	 */
	public function testDelete02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController delete Action
	 *
	 * Successfull
	 */
	public function testDelete03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\TestController delete Action
	 *
	 * Successfull with xmlhttprequest
	 */
	public function testDelete04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->xmlHttpRequest('GET', "/admin/test/delete/$id");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$content = json_decode($client->getResponse()->getContent(), true);

		$this->assertEquals(true, $content['success']);
	}
}