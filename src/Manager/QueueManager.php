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
use Doctrine\DBAL\Driver\PDOConnection;

/**
 * Class QueueManager.
 */
class QueueManager extends AbstractManager
{
    /**
     * @return int
     *
     * @throws DBALException
     */
    public function countAll(): int
    {
        $query = 'SELECT COUNT(*) FROM `messenger_messages`';

        $statement = $this->entityManager->getConnection()->prepare($query);

        $statement->execute();

        return (int) $statement->fetch(PDOConnection::FETCH_COLUMN);
    }
}
