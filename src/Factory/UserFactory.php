<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Factory;

use App\Entity\User;
use App\Factory\Interfaces\FactoryInterface;
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
