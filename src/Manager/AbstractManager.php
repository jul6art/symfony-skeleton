<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 17:11.
 */

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractManager.
 */
abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AbstractManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $entity
     * @param bool $flush
     *
     * @return bool
     */
    public function save($entity, bool $flush = true): bool
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function flush(): bool
    {
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param $entity
     * @param bool $flush
     *
     * @return bool
     */
    public function delete($entity, bool $flush = true): bool
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }

        return true;
    }
}
