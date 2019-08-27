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
use DateTime;
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
			'add_test[test][checkbox]' => '1',
			'add_test[test][switch]' => '1',
			'add_test[test][boolean]' => $this->faker->numberBetween(0, 1),
			'add_test[test][boolean2]' => $this->faker->numberBetween(0, 1),
			'add_test[test][radio]' => $this->faker->numberBetween(0, 2),
			'add_test[test][radio2]' => $this->faker->numberBetween(0, 2),
			'add_test[test][gender]' => $this->faker->randomElement(['m', 'f']),
			'add_test[test][gender2]' => $this->faker->randomElement(['m', 'f']),
			'add_test[test][phone]' => '+32499999999',
			'add_test[test][mobile]' => '+32499999999',
			'add_test[test][range]' => '50.00',
			'add_test[test][range2]' => '50.00, 80.00',
			'add_test[test][range3]' => '50.00',
			'add_test[test][range4]' => '50.00, 80.00',
			'add_test[test][date]' => (new DateTime('2019-08-01'))->format('d-m-Y'),
			'add_test[test][time]' => $this->faker->dateTime()->format('H:i'),
			'add_test[test][datetime]' => (new DateTime('2019-08-01'))->format('d-m-Y H:i'),
			'add_test[test][wysiwyg]' => $this->faker->text,
			'add_test[test][wysiwyg2]' => $this->faker->text,
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
			'edit_test[test][checkbox]' => '1',
			'edit_test[test][switch]' => '1',
			'edit_test[test][boolean]' => $this->faker->numberBetween(0, 1),
			'edit_test[test][boolean2]' => $this->faker->numberBetween(0, 1),
			'edit_test[test][radio]' => $this->faker->numberBetween(0, 2),
			'edit_test[test][radio2]' => $this->faker->numberBetween(0, 2),
			'edit_test[test][gender]' => $this->faker->randomElement(['m', 'f']),
			'edit_test[test][gender2]' => $this->faker->randomElement(['m', 'f']),
			'edit_test[test][phone]' => '+32499999999',
			'edit_test[test][mobile]' => '+32499999999',
			'edit_test[test][range]' => '50.00',
			'edit_test[test][range2]' => '50.00, 80.00',
			'edit_test[test][range3]' => '50.00',
			'edit_test[test][range4]' => '50.00, 80.00',
			'edit_test[test][date]' => (new DateTime('2019-08-01'))->format('d-m-Y'),
			'edit_test[test][time]' => $this->faker->dateTime()->format('H:i'),
			'edit_test[test][datetime]' => (new DateTime('2019-08-01'))->format('d-m-Y H:i'),
			'edit_test[test][wysiwyg]' => $this->faker->text,
			'edit_test[test][wysiwyg2]' => $this->faker->text,
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