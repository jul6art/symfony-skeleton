<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25.
 */

namespace App\Tests\Factory;

use App\Entity\Setting;
use App\Factory\SettingFactory;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingFactoryTest.
 */
class SettingFactoryTest extends TestCase
{
    /**
     * Test App\\Factory\\SettingFactory create Method.
     */
    public function testCreate()
    {
        $faker = Factory::create();

        $setting = (SettingFactory::create())
            ->setName($faker->name)
            ->setValue($faker->title);

        $this->assertInstanceOf(Setting::class, $setting);
    }
}
