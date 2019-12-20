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
 * Class ResettingControllerTest.
 */
class ResettingControllerTest extends WebTestCase
{
    use  TestTrait;
    /**
     * @var Generator
     */
    private $faker;

    /**
     * ResettingControllerTest constructor.
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
     * Test FOSUserBundle\\Controller\\ResettingController request Action.
     *
     * Invalid form
     */
    public function testRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/resetting/request');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="username"]')->count());

        $form = $crawler->filter('form[action="/resetting/send-email"] [type="submit"]')->form();
        $client->submit($form);

        $client->followRedirect();
        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('form[action="/resetting/send-email"]')->count());
    }

    /**
     * Test FOSUserBundle\\Controller\\ResettingController request Action.
     *
     * Successfull
     */
    public function testRequest02(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/resetting/request');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="username"]')->count());

        $form = $crawler->filter('form[action="/resetting/send-email"] [type="submit"]')->form();
        $client->submit($form, [
            'username' => UserUsername::USER_USERNAME_DEFAULT_USER,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(1, $crawler->filter('.msg')->count());
    }

    /**
     * Test FOSUserBundle\\Controller\\ResettingController reset Action.
     *
     * Invalid user
     */
    public function testReset(): void
    {
        $client = static::createClient();

        $user = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(User::class)
            ->findOneByUsername(UserUsername::USER_USERNAME_DEFAULT_ADMIN);

        $client->request('GET', '/resetting/reset/'.$user->getConfirmationToken());

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test FOSUserBundle\\Controller\\ResettingController reset Action.
     *
     * Successful
     */
    public function testReset02(): void
    {
        $client = static::createClient();

        $user = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(User::class)
            ->findOneByUsername(UserUsername::USER_USERNAME_DEFAULT_USER);

        $crawler = $client->request('GET', '/resetting/reset/'.$user->getConfirmationToken());

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(2, $crawler->filter('[name*="fos_user_resetting_form[plainPassword]"]')->count());

        $form = $crawler->filter('form[name="fos_user_resetting_form"] [type="submit"]')->form();
        $client->submit($form, [
            'fos_user_resetting_form[plainPassword][first]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
            'fos_user_resetting_form[plainPassword][second]' => UserPassword::USER_PASSWORD_DEFAULT_VALUE,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('a[href="/logout"]')->count());
    }
}
