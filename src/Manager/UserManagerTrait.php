<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait UserManagerTrait.
 */
trait UserManagerTrait
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @return UserManager
     */
    public function getUserManager(): UserManager
    {
        return $this->userManager;
    }

    /**
     * @param UserManager $userManager
     *
     * @required
     */
    public function setUserManager(UserManager $userManager): void
    {
        $this->userManager = $userManager;
    }
}
