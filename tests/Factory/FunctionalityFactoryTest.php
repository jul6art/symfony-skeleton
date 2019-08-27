<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25
 */

namespace App\Tests\Factory;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\Test;
use App\Factory\FunctionalityFactory;
use App\Factory\TestFactory;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class FunctionalityFactoryTest
 * @package App\Tests\Factory
 */
class FunctionalityFactoryTest extends WebTestCase
{
	/**
	 * FunctionalityFactoryTest constructor.
	 *
	 * @param null|string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( ?string $name = null, array $data = [], string $dataName = '' )
	{
		parent::__construct( $name, $data, $dataName );
	}

	/**
	 * Test App\\Factory\\FunctionalityFactory create Action
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