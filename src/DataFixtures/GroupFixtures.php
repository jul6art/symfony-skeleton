<?php

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

        foreach ($groups as $value) {
            $roleName = strtoupper($value);
            $group = (new Group($value))
                ->setRoles(["ROLE_$roleName"]);

            if (Group::GROUP_NAME_USER !== $value) {
                $group->addRole('ROLE_ALLOWED_TO_SWITCH');
            }

            $this->addReference("group_$value", $group);
            $manager->persist($group);
        }

        $manager->flush();
    }
}
