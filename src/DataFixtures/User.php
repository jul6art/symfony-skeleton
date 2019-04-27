<?php

namespace App\DataFixtures;

use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class User
 * @package App\DataFixtures
 */
class User extends Fixture implements DependentFixtureInterface
{
	const LIMIT = 30;

	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * User constructor.
	 *
	 * @param UserManager $userManager
	 */
	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	/**
	 * @param ObjectManager $manager
	 *
	 * @throws NonUniqueResultException
	 */
    public function load(ObjectManager $manager)
    {
        $user = $this->userManager
	        ->create()
	        ->setUsername('user')
	        ->setPlainPassword('user')
	        ->setEmail('user@vsweb.be');

        $this->setReference('user_user', $user);
        $manager->persist($user);

	    $admin = $this->userManager
		    ->createAdmin()
	        ->setUsername('admin')
	        ->setPlainPassword('admin')
	        ->setEmail('admin@vsweb.be');

	    $this->setReference('user_admin', $admin);
        $manager->persist($admin);

        $superAdmin = $this->userManager
	        ->createAdmin()
	        ->setUsername('superadmin')
	        ->setPlainPassword('superadmin')
	        ->setEmail('super_admin@vsweb.be')
	        ->addGroup($this->getReference('group_super_admin'));

	    $this->setReference('user_super_admin', $superAdmin);
        $manager->persist($superAdmin);

        for ($i = 0; $i < self::LIMIT; $i ++) {
	        $user = $this->userManager
		        ->create()
		        ->setUsername('user_' . $i)
		        ->setPlainPassword('user')
		        ->setEmail('user_' . $i . '@vsweb.be');

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
