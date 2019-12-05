<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GroupFixtures.
 */
class GroupFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groups = [
            Group::GROUP_NAME_USER,
            Group::GROUP_NAME_ADMIN,
            Group::GROUP_NAME_SUPER_ADMIN,
        ];

        array_walk($groups, function (string $name) use ($manager) {
            $roleName = strtoupper($name);
            $group = (new Group($name))
                ->setRoles(["ROLE_$roleName"]);

            if (Group::GROUP_NAME_USER !== $name) {
                $group->addRole('ROLE_ALLOWED_TO_SWITCH');
            }

            if (Group::GROUP_NAME_SUPER_ADMIN === $name) {
                $roleName = strtoupper(Group::GROUP_NAME_ADMIN);
                $group->addRole("ROLE_$roleName");
            }

            $this->addReference("group_$name", $group);
            $manager->persist($group);
        });

        $manager->flush();
    }
}
