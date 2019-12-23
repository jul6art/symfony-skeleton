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

/**
 * Class UserFactory
 * @package App\Factory
 */
final class UserFactory implements FactoryInterface
{
    /**
     * @param array $context
     * @return User|mixed
     */
    public static function create(array $context = [])
    {
        return (new User())
            ->addGroup($context['manager']->findOneByName($context['group']))
            ->setLocale($context['locale'])
            ->setTheme($context['theme']);
    }
}
