<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use SessionHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

/**
 * Class SessionManager.
 */
class SessionManager extends AbstractManager
{
    /**
     * @var SessionHandlerInterface
     */
    private $sessionHandler;

    /**
     * SessionManager constructor.
     *
     * @param EntityManagerInterface  $entityManager
     * @param SessionHandlerInterface $sessionHandler
     */
    public function __construct(EntityManagerInterface $entityManager, SessionHandlerInterface $sessionHandler)
    {
        parent::__construct($entityManager);
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * @param int $maxlifetime
     *
     * Removing outdated sessions from database
     *
     * @return $this
     *
     * @throws DBALException
     */
    public function clear(int $maxlifetime): self
    {
        /*
         * ONLY AVAILABLE FOR PDO SESSION HANDLER
         */
        if ($this->sessionHandler instanceof PdoSessionHandler) {
            $query = 'DELETE FROM `sessions` WHERE `sess_time` < :time';

            $statement = $this->entityManager->getConnection()->prepare($query);

            $statement->bindValue(':time', time() - $maxlifetime);
            $statement->execute();
        }

        return $this;
    }
}
