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
 * Class RegistrationControllerTest.
 */
class RegistrationControllerTest extends WebTestCase
{
    use  TestTrait;
    /**
     * @var Generator
     */
    private $faker;

    /**
     * RegistrationControllerTest constructor.
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
     * Test FOSuserBundle\\Controller\\RegisterController register Action.
     *
     * Invalid form
     */
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_registration_form[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_registration_form[plainPassword][first]"]')->count());

        $form = $crawler->filter('form[action="/register/"] [type="submit"]')->form();
        $crawler = $client->submit($form, [
            'fos_user_registration_form[username]' => $this->faker->userName,
        ]);

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertGreaterThan(0, $crawler->filter('.has-error')->count());
    }

    /**
     * Test FOSuserBundle\\Controller\\RegisterController register Action.
     *
     * Successfull
     */
    public function testRegister02()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register/');

        $this->save('result.html', $client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_registration_form[username]"]')->count());

        $this->assertEquals(1, $crawler->filter('[name="fos_user_registration_form[plainPassword][first]"]')->count());

        $form = $crawler->filter('form[action="/register/"] [type="submit"]')->form();
        $client->submit($form, [
            'fos_user_registration_form[gender]' => $this->faker->randomElement(['m', 'f']),
            'fos_user_registration_form[firstname]' => 'added',
            'fos_user_registration_form[lastname]' => $this->faker->lastName,
            'fos_user_registration_form[username]' => $this->faker->userName,
            'fos_user_registration_form[email]' => $this->faker->email,
            'fos_user_registration_form[plainPassword][first]' => User::DEFAULT_PASSWORD,
            'fos_user_registration_form[plainPassword][second]' => User::DEFAULT_PASSWORD,
        ]);

        $crawler = $client->followRedirect();

        $this->save('result02.html', $client->getResponse()->getContent());

        $this->assertEquals(0, $crawler->filter('.has-error')->count());
    }
}
