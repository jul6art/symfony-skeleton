<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 13:22.
 */

namespace App\MessageHandler;

use App\Manager\Traits\SessionManagerAwareTrait;
use App\Message\PurgeSessionsMessage;
use Doctrine\DBAL\DBALException;

/**
 * Class PurgeSessionsMessageHandler.
 */
class PurgeSessionsMessageHandler extends AbstractMessageHandler
{
    use SessionManagerAwareTrait;

    /**
     * @var int
     */
    private $session_lifetime;

    /**
     * PurgeSessionsMessageHandler constructor.
     *
     * @param int $session_lifetime
     */
    public function __construct(int $session_lifetime)
    {
        $this->session_lifetime = $session_lifetime;
    }

    /**
     * @param PurgeSessionsMessage $message
     *
     * @throws DBALException
     */
    public function __invoke(PurgeSessionsMessage $message)
    {
        try {
            $this->sessionManager->purge($this->session_lifetime);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
