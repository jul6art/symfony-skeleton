<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25.
 */

namespace App\Tests\Factory;

use App\Entity\Functionality;
use App\Factory\FunctionalityFactory;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionalityFactoryTest.
 */
class FunctionalityFactoryTest extends TestCase
{
    /**
     * Test App\\Factory\\FunctionalityFactory create Method.
     */
    public function testCreate()
    {
        $faker = Factory::create();

        $functionality = (FunctionalityFactory::create())
            ->setName($faker->name)
            ->setActive(true);

        $this->assertInstanceOf(Functionality::class, $functionality);
    }
}
