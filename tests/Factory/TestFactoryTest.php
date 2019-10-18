<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25.
 */

namespace App\Tests\Factory;

use App\Entity\Test;
use App\Factory\TestFactory;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TestFactoryTest.
 */
class TestFactoryTest extends WebTestCase
{
    /**
     * TestFactoryTest constructor.
     *
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test App\\Factory\\TestFactory create Action.
     */
    public function testCreate()
    {
        $faker = Factory::create();

        $test = (TestFactory::create())
            ->setName($faker->name)
            ->setContent($faker->text);

        $this->assertInstanceOf(Test::class, $test);
    }
}
