<?php

namespace App\DataFixtures;

use App\Manager\MaintenanceManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class MaintenanceFixtures.
 */
class MaintenanceFixtures extends Fixture
{
    use MaintenanceManagerTrait;

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $maintenance = $this->maintenanceManager
            ->create();

        $this->setReference('maintenance', $maintenance);
        $manager->persist($maintenance);

        $manager->flush();
    }
}
