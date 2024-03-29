<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46.
 */

namespace App\Tests\Controller;

use App\Entity\Constants\FunctionalityName;
use App\Entity\Constants\SettingName;
use App\Entity\Constants\SettingValue;
use App\Entity\Constants\UserPassword;
use App\Entity\Constants\UserUsername;
use App\Entity\Functionality;
use App\Entity\Setting;
use App\Tests\TestTrait;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerTest.
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
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    /**
     * Test App\\Controller\\DefaultController index Action.
     *
     * User must be logged
     */
    public function testIndex(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController index Action.
     *
     * User has bad Roles
     */
    public function testIndex02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController index Action.
     *
     * Successfull
     */
    public function testIndex03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController locale Action.
     *
     * Invalid locale
     */
    public function testLocale(): void
    {
        $client = static::createClient();

        $client->request('GET', '/locale/es');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController locale Action.
     *
     * Successfull
     */
    public function testLocale02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/locale/de');

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController locale Action.
     *
     * Successfull
     */
    public function testLocale03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/locale/fr');

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController cache Action.
     *
     * User must be logged
     */
    public function testCache(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/cache');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController cache Action.
     *
     * User has bad Roles
     */
    public function testCache02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/cache');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController theme Action.
     *
     * User must be logged
     */
    public function testTheme(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/theme/blue');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController theme Action.
     *
     * Invalid theme
     */
    public function testTheme02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/theme/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController theme Action.
     *
     * Successfull
     */
    public function testTheme03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/theme/blue');

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController theme Action.
     *
     * Successfull
     */
    public function testTheme04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/theme/green');

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController theme Action.
     *
     * Successfull
     */
    public function testTheme05(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/theme/'.SettingValue::SETTING_VALUE_DEFAULT_THEME);

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * User must be logged
     */
    public function testFunctionality(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/functionality/blue');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * User has bad Roles
     */
    public function testFunctionality02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_MANAGE_SETTINGS)->getId();
        $client->request('GET', "/admin/functionality/$id/0");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Invalid functionality
     */
    public function testFunctionality03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/functionality/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func audit
     */
    public function testFunctionality04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_AUDIT)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func cache
     */
    public function testFunctionality05(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_CLEAR_CACHE)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func edit in place
     */
    public function testFunctionality06(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_EDIT_IN_PLACE)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func confirm delete
     */
    public function testFunctionality07(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_CONFIRM_DELETE)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func form watch
     */
    public function testFunctionality08(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_FORM_WATCHER)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func manage settings
     */
    public function testFunctionality09(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_MANAGE_SETTINGS)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func progressive web app
     */
    public function testFunctionality10(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_PROGRESSIVE_WEB_APP)->getId();
        $client->request('GET', "/admin/functionality/$id/0");

        $crawler = $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(0, $crawler->filter('link[rel="manifest"]')->count());

        $client->request('GET', "/admin/functionality/$id/1");

        $crawler = $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('link[rel="manifest"]')->count());
    }

    /**
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func swicth locale
     */
    public function testFunctionality11(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_SWITCH_LOCALE)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with func swicth theme
     */
    public function testFunctionality12(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_SWITCH_THEME)->getId();
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
     * Test App\\Controller\\DefaultController functionality Action.
     *
     * Successfull with ajax call
     */
    public function testFunctionality13(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Functionality::class)->findOneByName(FunctionalityName::FUNC_NAME_SWITCH_THEME)->getId();
        $client->xmlHttpRequest('GET', "/admin/functionality/$id/1");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * User must be logged
     */
    public function testSetting(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/setting/blue');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * User has bad roles
     */
    public function testSetting02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_PROJECT_NAME)->getId();
        $client->request('GET', "/admin/setting/$id/test");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Invalid setting
     */
    public function testSetting03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $client->request('GET', '/admin/setting/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with setting project name
     */
    public function testSetting04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_PROJECT_NAME)->getId();
        $value = SettingValue::SETTING_VALUE_PROJECT_NAME;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with setting base title
     */
    public function testSetting05(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_BASE_TITLE)->getId();
        $value = SettingValue::SETTING_VALUE_BASE_TITLE;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with setting default theme
     */
    public function testSetting06(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_DEFAULT_THEME)->getId();
        $value = SettingValue::SETTING_VALUE_DEFAULT_THEME;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with setting audit limit
     *
     */
    public function testSetting07(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_AUDIT_LIMIT)->getId();
        $value = SettingValue::SETTING_VALUE_AUDIT_LIMIT;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with setting toastr vertical position
     */
    public function testSetting08(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_TOASTR_VERTICAL_POSITION)->getId();
        $value = SettingValue::SETTING_VALUE_TOASTR_VERTICAL_POSITION;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with toastr horizontal position
     *
     */
    public function testSetting09(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_TOASTR_HORIZONTAL_POSITION)->getId();
        $value = SettingValue::SETTING_VALUE_TOASTR_HORIZONTAL_POSITION;
        $client->request('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\DefaultController setting Action.
     *
     * Successfull with ajax call
     *
     */
    public function testSetting10(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_ADMIN,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $id = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Setting::class)->findOneByName(SettingName::SETTING_NAME_TOASTR_HORIZONTAL_POSITION)->getId();
        $value = SettingValue::SETTING_VALUE_TOASTR_HORIZONTAL_POSITION;
        $client->xmlHttpRequest('GET', "/admin/setting/$id/$value");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
