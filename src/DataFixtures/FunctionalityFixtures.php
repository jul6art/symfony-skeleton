<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\Constants\FunctionalityName;
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;
use ReflectionException;

/**
 * Class FunctionalityFixtures.
 */
class FunctionalityFixtures extends Fixture
{
    use FunctionalityManagerAwareTrait;

    /**
     * @param ObjectManager $manager
     * @throws ReflectionException
     */
    public function load(ObjectManager $manager)
    {
        foreach (array_flip((new ReflectionClass(FunctionalityName::class))->getConstants()) as $key => $value) {
            $functionality = ($this->functionalityManager->create())
                ->setName($key);

            $this->setReference("func_$key", $functionality);
            $manager->persist($functionality);
        }

        $manager->flush();
    }
}
