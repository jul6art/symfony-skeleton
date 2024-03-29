<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46.
 */

namespace App\Tests\Controller;

use App\Entity\Constants\UserPassword;
use App\Entity\Constants\UserUsername;
use App\Entity\User;
use App\Tests\TestTrait;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangePasswordControllerTest.
 */
class ChangePasswordControllerTest extends WebTestCase
{
    use  TestTrait;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * ChangePasswordControllerTest constructor.
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
     * Test FOSuserBundle\\Controller\\ChangePasswordController changePassword Action.
     *
     * User must be logged
     */
    public function testChangePassword(): void
    {
        $client = static::createClient();

        $client->request('GET', '/profile/change-password');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test FOSuserBundle\\Controller\\ChangePasswordController changePassword Action.
     *
     * Invalid form
     */
    public function testChangePassword02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $crawler = $client->request('GET', '/profile/change-password');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_change_password_form[current_password]"]')->count());

        $this->assertEquals(2, $crawler->filter('[name*="fos_user_change_password_form[plainPassword]"]')->count());

        $form = $crawler->filter('form[name="fos_user_change_password_form"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'fos_user_change_password_form[current_password]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test FOSuserBundle\\Controller\\ChangePasswordController changePassword Action.
     *
     * Successful
     */
    public function testChangePassword03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserUsername::USER_USERNAME_DEFAULT_USER,
            'PHP_AUTH_PW' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $crawler = $client->request('GET', '/profile/change-password');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_change_password_form[current_password]"]')->count());

        $this->assertEquals(2, $crawler->filter('[name*="fos_user_change_password_form[plainPassword]"]')->count());

        $form = $crawler->filter('form[name="fos_user_change_password_form"] [type="submit"]')->form();
        $client->submit($form, [
            'fos_user_change_password_form[current_password]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
            'fos_user_change_password_form[plainPassword][first]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
            'fos_user_change_password_form[plainPassword][second]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.has-error')->count());
    }
}
