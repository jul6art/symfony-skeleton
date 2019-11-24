<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 13:22.
 */

namespace App\MessageHandler;

use App\Manager\SessionManagerTrait;
use App\Message\ClearSessionsMessage;
use Doctrine\DBAL\DBALException;

/**
 * Class ClearSessionsMessageHandler.
 */
class ClearSessionsMessageHandler
{
    use SessionManagerTrait;

    /**
     * @var int
     */
    private $session_lifetime;

    /**
     * ClearSessionsMessageHandler constructor.
     *
     * @param int $session_lifetime
     */
    public function __construct(int $session_lifetime)
    {
        $this->session_lifetime = $session_lifetime;
    }

    /**
     * @param ClearSessionsMessage $message
     *
     * @throws DBALException
     */
    public function __invoke(ClearSessionsMessage $message)
    {
        $this->sessionManager->clear($this->session_lifetime);
    }
}
