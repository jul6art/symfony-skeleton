<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Maintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maintenance[]    findAll()
 * @method Maintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    /**
     * TestRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
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
