<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManagerAwareTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    use UserManagerAwareTrait;

    private const LIMIT = 30;
    private const GENDER_CHOICES = ['m', 'f'];

    /**
     * @param ObjectManager $manager
     *
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        /**
         * USER.
         */
        $user = $this->userManager
            ->create()
            ->setUsername('user')
            ->setEmail('user@devinthehood.com');

        $this->updateUser($user, $faker);

        $this->setReference('user_user', $user);
        $manager->persist($user);

        /**
         * ADMIN.
         */
        $admin = $this->userManager
            ->createAdmin()
            ->setUsername('admin')
            ->setEmail('admin@devinthehood.com');

        $this->updateUser($admin, $faker);

        $this->setReference('user_admin', $admin);
        $manager->persist($admin);

        /**
         * SUPER ADMIN.
         */
        $superAdmin = $this->userManager
            ->createSuperAdmin()
            ->setUsername('superadmin')
            ->setEmail('super_admin@devinthehood.com');

        $this->updateUser($superAdmin, $faker);

        $this->setReference('user_super_admin', $superAdmin);
        $manager->persist($superAdmin);

        /*
         * CITIZENS
         */
        for ($i = 0; $i < self::LIMIT; ++$i) {
            $user = $this->userManager
                ->create()
                ->setUsername($faker->unique()->userName)
                ->setEmail($faker->unique()->safeEmail);

            $this->updateUser($user, $faker);

            $this->setReference("user_user_$i", $user);
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

    /**
     * @param User      $user
     * @param Generator $faker
     *
     * @return User
     */
    private function updateUser(User $user, Generator $faker): User
    {
        return $user
            ->setEnabled(true)
            ->setGender($faker->randomElement(self::GENDER_CHOICES))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPlainPassword(User::DEFAULT_PASSWORD);
    }
}
