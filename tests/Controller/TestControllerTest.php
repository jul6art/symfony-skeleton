<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 * @package App\Tests\Controller
 */
class TestControllerTest extends WebTestCase
{
	/**
	 * Test App\\Controller\\UserController index Action
	 *
	 * Test must be logged
	 */
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/test/');

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

		$client->request('GET', '/admin/test/');

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

		$client->request('GET', '/admin/test/');

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

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

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

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

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

		$tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Test::class)->findAll();
		$id = end($tests)->getId();

		$client->request('GET', "/admin/test/delete/$id");

		$client->followRedirect();

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}
}