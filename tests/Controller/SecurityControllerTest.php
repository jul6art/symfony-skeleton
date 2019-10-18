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
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    use  TestTrait;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * SecurityControllerTest constructor.
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
     * Test FOSuserBundle\\Controller\\SecurityController login Action.
     *
     * Invalid form
     */
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="_username"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="_password"]')->count());

        $form = $crawler->filter('form[action="/login_check"] [type="submit"]')->form();
        $client->submit($form, [
            '_username' => $this->faker->userName,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.error')->count());
    }

    /**
     * Test FOSuserBundle\\Controller\\SecurityController login Action.
     *
     * Successfull
     */
    public function testLogin02()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="_username"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="_password"]')->count());

        $form = $crawler->filter('form[action="/login_check"] [type="submit"]')->form();
        $client->submit($form, [
            '_username' => User::DEFAULT_ADMIN_USERNAME,
            '_password' => User::DEFAULT_PASSWORD,
        ]);

        $client->followRedirect();

        $crawler = $client->request('GET', '/profile/');

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.error')->count());

        $this->assertGreaterThan(0, $crawler->filter('a[href="/logout"]')->count());
    }
}
