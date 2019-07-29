<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Tests\TestTrait;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerTest
 * @package App\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase
{
	use TestTrait;

	/**
	 * @var Generator
	 */
	private $faker;

	/**
	 * DefaultControllerTest constructor.
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
	 * Test App\\Controller\\DefaultController index Action
	 *
	 * User must be logged
	 */
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController index Action
	 *
	 * User has bad Roles
	 */
	public function testIndex02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController index Action
	 *
	 * Successfull
	 */
	public function testIndex03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController locale Action
	 *
	 * Invalid locale
	 */
	public function testLocale()
	{
		$client = static::createClient();

		$client->request('GET', '/locale/es');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController locale Action
	 *
	 * Successfull
	 */
	public function testLocale02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/locale/de');

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController cache Action
	 *
	 * User must be logged
	 */
	public function testCache()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/cache');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController cache Action
	 *
	 * User has bad Roles
	 */
	public function testCache02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/cache');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController theme Action
	 *
	 * User must be logged
	 */
	public function testTheme()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/theme/blue');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController theme Action
	 *
	 * User has bad Roles
	 */
	public function testTheme02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/theme/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController theme Action
	 *
	 * Successfull
	 */
	public function testTheme03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => 'vsweb',
		]);

		$client->request('GET', '/admin/theme/blue');

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}
}