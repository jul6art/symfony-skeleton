<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:13.
 */

namespace App\Manager;

use App\Entity\Maintenance;
use App\Repository\MaintenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class MaintenanceManager.
 */
class MaintenanceManager extends AbstractManager
{
    /**
     * @var MaintenanceRepository
     */
    private $maintenanceRepository;

    /**
     * GroupManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->maintenanceRepository = $this->entityManager->getRepository(Maintenance::class);
    }

    /**
     * @param string $name
     *
     * @return Maintenance|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByLatest(): ?Maintenance
    {
        return $this->maintenanceRepository->findOneByLatest();
    }
}
