<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25.
 */

namespace App\Tests\Factory;

use App\Entity\Setting;
use App\Entity\Test;
use App\Factory\SettingFactory;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SettingFactoryTest.
 */
class SettingFactoryTest extends WebTestCase
{
    /**
     * SettingFactoryTest constructor.
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
     * Test App\\Factory\\SettingFactory create Action.
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
