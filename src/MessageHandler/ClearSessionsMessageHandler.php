<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 13:22.
 */

namespace App\MessageHandler;

use App\Message\ClearSessionsMessage;
use Psr\Log\LoggerInterface;

/**
 * Class ClearSessionsMessageHandler.
 */
class ClearSessionsMessageHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ClearSessionsMessageHandler constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ClearSessionsMessage $message
     */
    public function __invoke(ClearSessionsMessage $message)
    {
        $this->logger->critical('test');
    }
}
