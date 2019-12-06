<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\Functionality;
use App\Manager\FunctionalityManagerAwareTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityFixtures.
 */
class FunctionalityFixtures extends Fixture
{
    use FunctionalityManagerAwareTrait;

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $functionalities = [
            Functionality::FUNC_AUDIT,
            Functionality::FUNC_CLEAR_CACHE,
            Functionality::FUNC_EDIT_IN_PLACE,
            Functionality::FUNC_CONFIRM_DELETE,
            Functionality::FUNC_FORM_WATCHER,
            Functionality::FUNC_MAINTENANCE,
            Functionality::FUNC_MANAGE_SETTINGS,
            Functionality::FUNC_PROGRESSIVE_WEB_APP,
            Functionality::FUNC_SWITCH_LOCALE,
            Functionality::FUNC_SWITCH_THEME,
        ];

        array_walk($functionalities, function (string $name) use ($manager) {
            $functionality = ($this->functionalityManager->create())
                ->setName($name);

            $this->setReference("func_$name", $functionality);
            $manager->persist($functionality);
        });

        $manager->flush();
    }
}
