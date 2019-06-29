<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    use UserManagerTrait;

    const LIMIT = 30;

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = $this->userManager
            ->create()
            ->setUsername('user')
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPlainPassword(User::DEFAULT_PASSWORD)
            ->setEmail('user@vsweb.be');

        $this->setReference('user_user', $user);
        $manager->persist($user);

        $admin = $this->userManager
            ->createAdmin()
            ->setUsername('admin')
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPlainPassword(User::DEFAULT_PASSWORD)
            ->setEmail('admin@vsweb.be');

        $this->setReference('user_admin', $admin);
        $manager->persist($admin);

        $superAdmin = $this->userManager
            ->createAdmin()
            ->setUsername('superadmin')
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPlainPassword(User::DEFAULT_PASSWORD)
            ->setEmail('super_admin@vsweb.be')
            ->addGroup($this->getReference('group_super_admin'));

        $this->setReference('user_super_admin', $superAdmin);
        $manager->persist($superAdmin);

        for ($i = 0; $i < self::LIMIT; ++$i ) {
            $user = $this->userManager
                ->create()
                ->setUsername($faker->userName)
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPlainPassword(User::DEFAULT_PASSWORD)
                ->setEmail($faker->email);

            $this->setReference("user_user_{$i}", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            GroupFixtures::class,
        ];
    }
}
