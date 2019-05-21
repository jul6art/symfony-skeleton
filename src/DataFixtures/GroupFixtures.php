<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GroupFixtures
 * @package App\DataFixtures
 */
class GroupFixtures extends Fixture
{
	/**
	 * @param ObjectManager $manager
	 */
    public function load(ObjectManager $manager)
    {
	    $groups = [
		    'user',
		    'admin',
		    'super_admin',
	    ];

	    foreach ($groups as $value) {
		    $group = (new Group($value))
			    ->setRoles([
		    	    sprintf('ROLE_%s', strtoupper($value)),
		        ]);

		    $this->addReference('group_' . $value, $group);
		    $manager->persist($group);
	    }

	    $manager->flush();
    }
}
