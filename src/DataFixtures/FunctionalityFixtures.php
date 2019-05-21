<?php

namespace App\DataFixtures;

use App\Entity\Functionality;
use App\Manager\FunctionalityManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityFixtures
 * @package App\DataFixtures
 */
class FunctionalityFixtures extends Fixture
{
	/**
	 * @var FunctionalityManager
	 */
	private $functionalityManager;

	/**
	 * Functionality constructor.
	 *
	 * @param FunctionalityManager $functionalityManager
	 */
	public function __construct(FunctionalityManager $functionalityManager)
	{
		$this->functionalityManager = $functionalityManager;
	}

	/**
	 * @param ObjectManager $manager
	 *
	 * @throws NonUniqueResultException
	 */
    public function load(ObjectManager $manager)
    {
	    $functionalities = [
		    Functionality::FUNC_SWITCH_THEME,
	    ];

        foreach ($functionalities as $value) {
	        $functionality = ($this->functionalityManager->create())
		        ->setName($value);

	        $this->setReference('func_' . $value, $functionality);
	        $manager->persist($functionality);
        }

        $manager->flush();
    }
}
