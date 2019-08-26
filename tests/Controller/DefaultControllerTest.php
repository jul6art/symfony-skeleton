<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46
 */

namespace App\Tests\Controller;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Tests\TestTrait;
use Doctrine\ORM\ORMException;
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
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
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
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
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
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/locale/de');

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController locale Action
	 *
	 * Successfull
	 */
	public function testLocale03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/locale/fr');

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

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
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
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
	 * Invalid theme
	 */
	public function testTheme02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
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
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/theme/blue');

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * User must be logged
	 */
	public function testFunctionality()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/functionality/blue');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * User has bad Roles
	 */
	public function testFunctionality02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_MANAGE_SETTINGS)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Invalid functionality
	 */
	public function testFunctionality03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/functionality/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func audit
	 */
	public function testFunctionality04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_AUDIT)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func cache
	 */
	public function testFunctionality05()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_CLEAR_CACHE)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func edit in place
	 */
	public function testFunctionality06()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_EDIT_IN_PLACE)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func confirm delete
	 */
	public function testFunctionality07()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_CONFIRM_DELETE)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func form watch
	 */
	public function testFunctionality08()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_FORM_WATCHER)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func manage settings
	 */
	public function testFunctionality09()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_MANAGE_SETTINGS)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func swicth locale
	 */
	public function testFunctionality10()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_SWITCH_LOCALE)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with func swicth theme
	 */
	public function testFunctionality11()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_SWITCH_THEME)->getId();
		$client->request('GET', "/admin/functionality/$id/0");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$client->request('GET', "/admin/functionality/$id/1");

		$client->followRedirect();

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController functionality Action
	 *
	 * Successfull with ajax call
	 */
	public function testFunctionality12()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(Functionality::FUNC_SWITCH_THEME)->getId();
		$client->xmlHttpRequest('GET', "/admin/functionality/$id/1");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * User must be logged
	 */
	public function testSetting()
	{
		$client = static::createClient();

		$client->request('GET', '/admin/setting/blue');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * User has bad roles
	 *
	 * @throws ORMException
	 */
	public function testSetting02()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_PROJECT_NAME)->getId();
		$client->request('GET', "/admin/setting/$id/test");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Invalid setting
	 */
	public function testSetting03()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$client->request('GET', '/admin/setting/-1');

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with setting project name
	 *
	 * @throws ORMException
	 */
	public function testSetting04()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_PROJECT_NAME)->getId();
		$value = Setting::SETTING_PROJECT_NAME_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with setting base title
	 *
	 * @throws ORMException
	 */
	public function testSetting05()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_BASE_TITLE)->getId();
		$value = Setting::SETTING_BASE_TITLE_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with setting default theme
	 *
	 * @throws ORMException
	 */
	public function testSetting06()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_DEFAULT_THEME)->getId();
		$value = Setting::SETTING_DEFAULT_THEME_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with setting audit limit
	 *
	 * @throws ORMException
	 */
	public function testSetting07()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_AUDIT_LIMIT)->getId();
		$value = Setting::SETTING_AUDIT_LIMIT_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with setting toastr vertical position
	 *
	 * @throws ORMException
	 */
	public function testSetting08()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_TOASTR_VERTICAL_POSITION)->getId();
		$value = Setting::SETTING_TOASTR_VERTICAL_POSITION_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with toastr horizontal position
	 *
	 * @throws ORMException
	 */
	public function testSetting09()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_TOASTR_HORIZONTAL_POSITION)->getId();
		$value = Setting::SETTING_TOASTR_HORIZONTAL_POSITION_VALUE;
		$client->request('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$client->followRedirect();

		$this->save('result02.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test App\\Controller\\DefaultController setting Action
	 *
	 * Successfull with ajax call
	 *
	 * @throws ORMException
	 */
	public function testSetting10()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
			'PHP_AUTH_PW'   => User::DEFAULT_PASSWORD,
		]);

		$id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(Setting::SETTING_TOASTR_HORIZONTAL_POSITION)->getId();
		$value = Setting::SETTING_TOASTR_HORIZONTAL_POSITION_VALUE;
		$client->xmlHttpRequest('GET', "/admin/setting/$id/$value");

		$this->save('result.html', $client->getResponse()->getContent());

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}
}