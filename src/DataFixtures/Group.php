<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Group extends Fixture
{
    public function load(ObjectManager $manager)
    {
	    $groups = [
		    'user',
		    'admin',
		    'super_admin',
	    ];

	    foreach ($groups as $value) {
		    $group = (new \App\Entity\Group($value))
			    ->setRoles([
		    	    sprintf('ROLE_%s', strtoupper($value)),
		        ]);

		    $this->addReference('group_' . $value, $group);
		    $manager->persist($group);
	    }

	    $manager->flush();
    }
}
