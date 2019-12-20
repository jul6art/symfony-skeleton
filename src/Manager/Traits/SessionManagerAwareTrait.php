<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\SessionManager;

/**
 * Trait SessionManagerAwareTrait.
 */
trait SessionManagerAwareTrait
{
    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }

    /**
     * @param SessionManager $sessionManager
     *
     * @required
     */
    public function setSessionManager(SessionManager $sessionManager): void
    {
        $this->sessionManager = $sessionManager;
    }
}
