<?php

namespace App\DataFixtures;

use App\Manager\TestManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;

/**
 * Class TestFixtures
 * @package App\DataFixtures
 */
class TestFixtures extends Fixture
{
	use TestManagerTrait;

	const LIMIT = 100;

	/**
	 * @param ObjectManager $manager
	 *
	 * @throws NonUniqueResultException
	 */
    public function load(ObjectManager $manager)
    {
	    $faker = Factory::create();

        for ($i = 0; $i < self::LIMIT; $i ++) {
	        $test = $this->testManager
		        ->create()
		        ->setName($faker->name);

	        $this->setReference('test_' . $i, $test);
	        $manager->persist($test);
        }

        $manager->flush();
    }
}