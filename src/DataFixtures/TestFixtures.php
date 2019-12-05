<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Manager\TestManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;

/**
 * Class TestFixtures.
 */
class TestFixtures extends Fixture
{
    use TestManagerTrait;

    private const LIMIT = 100;

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::LIMIT; ++$i) {
            $test = $this->testManager
                ->create()
                ->setName($faker->name)
                ->setContent($faker->text);

            $this->setReference("test_$i", $test);
            $manager->persist($test);
        }

        $manager->flush();
    }
}
