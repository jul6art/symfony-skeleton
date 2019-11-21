<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
