<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\Constants\GroupName;
use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;
use ReflectionException;

/**
 * Class GroupFixtures.
 */
class GroupFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws ReflectionException
     */
    public function load(ObjectManager $manager)
    {
        foreach (array_flip((new ReflectionClass(GroupName::class))->getConstants()) as $key => $value) {
            $roleName = strtoupper($key);
            $group = (new Group($key))
                ->setRoles(["ROLE_$roleName"]);

            if (GroupName::GROUP_NAME_USER !== $key) {
                $group->addRole('ROLE_ALLOWED_TO_SWITCH');
            }

            if (GroupName::GROUP_NAME_SUPER_ADMIN === $key) {
                $roleName = strtoupper(GroupName::GROUP_NAME_ADMIN);
                $group->addRole("ROLE_$roleName");
            }

            $this->addReference("group_$key", $group);
            $manager->persist($group);
        }

        $manager->flush();
    }
}
