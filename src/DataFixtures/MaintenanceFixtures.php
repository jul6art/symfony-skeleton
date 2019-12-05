<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

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
