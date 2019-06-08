<?php

namespace App\DataFixtures;

use App\Entity\Functionality;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityFixtures.
 */
class FunctionalityFixtures extends Fixture
{
    use FunctionalityManagerTrait;

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $functionalities = [
            Functionality::FUNC_SWITCH_THEME,
            Functionality::FUNC_SWITCH_LOCALE,
            Functionality::FUNC_CLEAR_CACHE,
            Functionality::FUNC_CONFIRM_DELETE,
            Functionality::FUNC_FORM_WATCHER,
            Functionality::FUNC_MANAGE_SETTINGS,
        ];

        foreach ($functionalities as $value) {
            $functionality = ($this->functionalityManager->create())
                ->setName($value);

            $this->setReference('func_'.$value, $functionality);
            $manager->persist($functionality);
        }

        $manager->flush();
    }
}
