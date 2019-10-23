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
use PHPUnit\Framework\TestCase;

/**
 * Class TestFactoryTest.
 */
class TestFactoryTest extends TestCase
{
    /**
     * Test App\\Factory\\TestFactory create Method.
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
