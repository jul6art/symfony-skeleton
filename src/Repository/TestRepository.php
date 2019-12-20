<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\Test;
use App\Repository\Traits\RepositoryAwareTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    use RepositoryAwareTrait;

    /**
     * TestRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    /**
     * @param string $name
     *
     * @return Test|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Test
    {
        $builder = $this->createQueryBuilder('t');

        $this
            ->filterLowercase($builder, 't.name', $name);

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
        $builder = $this->createQueryBuilder('t');

        $builder->select($builder->expr()->count('t'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
