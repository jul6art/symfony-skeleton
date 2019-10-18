<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25.
 */

namespace App\Factory;

use App\Entity\User;
use App\Manager\GroupManager;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserFactory.
 */
class UserFactory implements FactoryInterface
{
    /**
     * @param GroupManager $groupManager
     * @param string       $group
     * @param string       $locale
     * @param string       $defaultTheme
     *
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public static function build(GroupManager $groupManager, string $group, string $locale, string $defaultTheme): User
    {
        $user = self::create();

        $user
            ->addGroup($groupManager->findOneByName($group))
            ->setLocale($locale)
            ->setTheme($defaultTheme);

        return $user;
    }

    /**
     * @return User|mixed
     */
    public static function create()
    {
        return new User();
    }
}
