<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use App\Entity\Maintenance;
use App\Factory\MaintenanceFactory;
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
     * @return Maintenance
     */
    public function create(): Maintenance
    {
        return MaintenanceFactory::create();
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
