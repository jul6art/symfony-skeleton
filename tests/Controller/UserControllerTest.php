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
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    use TestTrait;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * UserControllerTest constructor.
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
     * Test App\\Controller\\UserController index Action.
     *
     * User must be logged
     */
    public function testIndex(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/user/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController index Action.
     *
     * User has bad Roles
     */
    public function testIndex02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $client->request('GET', '/admin/user/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController index Action.
     *
     * Successfull
     */
    public function testIndex03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $client->request('GET', '/admin/user/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController add Action.
     *
     * User must be logged
     */
    public function testAdd(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/user/add');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController add Action.
     *
     * User has bad Roles
     */
    public function testAdd02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $client->request('GET', '/admin/user/add');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController add Action.
     *
     * Invalid form
     */
    public function testAdd03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/admin/user/add');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="add_user[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="add_user[email]"]')->count());

        $form = $crawler->filter('form[name="add_user"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'add_user[username]' => $this->faker->userName,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test App\\Controller\\UserController add Action.
     *
     * Successfull
     */
    public function testAdd04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/admin/user/add');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="add_user[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="add_user[email]"]')->count());

        $form = $crawler->filter('form[name="add_user"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'add_user[gender]' => 'm',
            'add_user[username]' => $this->faker->userName,
            'add_user[firstname]' => $this->faker->firstName,
            'add_user[lastname]' => $this->faker->lastName,
            'add_user[email]' => $this->faker->email,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test App\\Controller\\UserController show Action.
     *
     * User must be logged
     */
    public function testShow(): void
    {
        $client = static::createClient();

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/view/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController show Action.
     *
     * User has bad Roles
     */
    public function testShow02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/view/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController show Action.
     *
     * Successfull
     */
    public function testShow03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/view/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController show Action.
     *
     * Not found
     */
    public function testShow04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $client->request('GET', '/admin/user/view/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController edit Action.
     *
     * User must be logged
     */
    public function testEdit(): void
    {
        $client = static::createClient();

        $tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($tests)->getId();

        $client->request('GET', "/admin/user/edit/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController edit Action.
     *
     * User has bad Roles
     */
    public function testEdit02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($tests)->getId();

        $client->request('GET', "/admin/user/edit/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController edit Action.
     *
     * Invalid form
     */
    public function testEdit03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($tests)->getId();

        $crawler = $client->request('GET', "/admin/user/edit/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="edit_user[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="edit_user[email]"]')->count());

        $form = $crawler->filter('form[name="edit_user"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'edit_user[firstname]' => 'INVALID_INVALID_INVALID_INVALID_INVALID_INVALID_INVALID_INVALID',
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test App\\Controller\\UserController edit Action.
     *
     * Not found
     */
    public function testEdit04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/admin/user/edit/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController edit Action.
     *
     * Successfull
     */
    public function testEdit05(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $tests = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($tests)->getId();

        $crawler = $client->request('GET', "/admin/user/edit/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="edit_user[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="edit_user[email]"]')->count());

        $form = $crawler->filter('form[name="edit_user"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'edit_user[gender]' => 'm',
            'edit_user[username]' => $this->faker->userName,
            'edit_user[firstname]' => $this->faker->firstName,
            'edit_user[lastname]' => $this->faker->lastName,
            'edit_user[email]' => $this->faker->email,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test App\\Controller\\UserController delete Action.
     *
     * User must be logged
     */
    public function testDelete(): void
    {
        $client = static::createClient();

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/delete/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController delete Action.
     *
     * User has bad Roles
     */
    public function testDelete02(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_USER_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/delete/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController delete Action.
     *
     * Successfull
     */
    public function testDelete03(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findAll();
        $id = end($users)->getId();

        $client->request('GET', "/admin/user/delete/$id");

        $this->save('result.html', $client->getResponse()->getContent());

        $client->followRedirect();

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test App\\Controller\\UserController delete Action.
     *
     * Not found
     */
    public function testDelete04(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => User::DEFAULT_ADMIN_USERNAME,
            'PHP_AUTH_PW' => User::DEFAULT_PASSWORD,
        ]);

        $client->request('GET', '/admin/user/delete/-1');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }
}
