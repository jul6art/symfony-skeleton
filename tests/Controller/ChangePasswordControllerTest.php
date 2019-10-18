<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 18:46.
 */

namespace App\Tests\Controller;

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
    public function testChangePassword()
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
    public function testChangePassword02()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/profile/change-password');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_change_password_form[current_password]"]')->count());

        $this->assertEquals(2, $crawler->filter('[name*="fos_user_change_password_form[plainPassword]"]')->count());

        $form = $crawler->filter('form[name="fos_user_change_password_form"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'fos_user_change_password_form[current_password]' => User::DEFAULT_PASSWORD,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test FOSuserBundle\\Controller\\ChangePasswordController changePassword Action.
     *
     * Successful
     */
    public function testChangePassword03()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/profile/change-password');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_change_password_form[current_password]"]')->count());

        $this->assertEquals(2, $crawler->filter('[name*="fos_user_change_password_form[plainPassword]"]')->count());

        $form = $crawler->filter('form[name="fos_user_change_password_form"] [type="submit"]')->form();
        $client->submit($form, [
            'fos_user_change_password_form[current_password]' => User::DEFAULT_PASSWORD,
            'fos_user_change_password_form[plainPassword][first]' => User::DEFAULT_PASSWORD,
            'fos_user_change_password_form[plainPassword][second]' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.has-error')->count());
    }
}
