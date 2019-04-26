<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class User
 * @package App\DataFixtures
 */
class User extends Fixture implements DependentFixtureInterface
{
	const LIMIT = 30;

	/**
	 * @param ObjectManager $manager
	 */
    public function load(ObjectManager $manager)
    {
        $user = (new \App\Entity\User())
	        ->setUsername('user')
	        ->setPlainPassword('user')
	        ->setEmail('user@vsweb.be')
	        ->setEnabled(true)
	        ->addGroup($this->getReference('group_user'));

        $this->setReference('user_user', $user);
        $manager->persist($user);

        $admin = (new \App\Entity\User())
	        ->setUsername('admin')
	        ->setPlainPassword('admin')
	        ->setEmail('admin@vsweb.be')
	        ->setEnabled(true)
	        ->addGroup($this->getReference('group_admin'));

	    $this->setReference('user_admin', $admin);
        $manager->persist($admin);

        $superAdmin = (new \App\Entity\User())
	        ->setUsername('superadmin')
	        ->setPlainPassword('superadmin')
	        ->setEmail('super_admin@vsweb.be')
	        ->setEnabled(true)
	        ->addGroup($this->getReference('group_admin'))
	        ->addGroup($this->getReference('group_super_admin'));

	    $this->setReference('user_super_admin', $superAdmin);
        $manager->persist($superAdmin);

        for ($i = 0; $i < self::LIMIT; $i ++) {
	        $user = (new \App\Entity\User())
		        ->setUsername('user_' . $i)
		        ->setPlainPassword('user')
		        ->setEmail('user_' . $i . '@vsweb.be')
		        ->setEnabled(true)
		        ->addGroup($this->getReference('group_user'));

	        $this->setReference('user_user_' . $i, $user);
	        $manager->persist($user);
        }

        $manager->flush();
    }

	/**
	 * @return array
	 */
    public function getDependencies() {
	    return [
	    	Group::class,
	    ];
    }
}
