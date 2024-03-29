<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\Maintenance;
use App\Repository\Traits\RepositoryAwareTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maintenance[]    findAll()
 * @method Maintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceRepository extends ServiceEntityRepository
{
    use RepositoryAwareTrait;

    /**
     * MaintenanceRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maintenance::class);
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
        $builder = $this->createQueryBuilder('m');

        $this
            ->filterByLatest($builder, 'm.id');

        return $builder->getQuery()->getOneOrNullResult();
    }
}
