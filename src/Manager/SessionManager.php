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

/**
 * Class SessionManager.
 */
class SessionManager extends AbstractManager
{
    /**
     * @param int $maxlifetime
     *
     * Removing outdated sessions from database
     *
     * @return $this
     *
     * @throws DBALException
     */
    public function purge(int $maxlifetime): self
    {
        $query = 'DELETE FROM `sessions` WHERE `sess_time` < :time';

        $statement = $this->entityManager->getConnection()->prepare($query);

        $statement->bindValue(':time', time() - $maxlifetime);
        $statement->execute();

        return $this;
    }
}
