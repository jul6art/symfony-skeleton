<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\Functionality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Functionality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Functionality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Functionality[]    findAll()
 * @method Functionality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunctionalityRepository extends ServiceEntityRepository
{
    use RepositoryAwareTrait;

    /**
     * FunctionalityRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Functionality::class);
    }

    /**
     * @param string $name
     *
     * @return Functionality|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Functionality
    {
        $builder = $this->createQueryBuilder('f');

        $this
            ->filterLowercase($builder, 'f.name', $name);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('f');

        $builder->select($builder->expr()->count('f'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
