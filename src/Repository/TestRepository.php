<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    /**
     * TestRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
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
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('t');

        $builder->select($builder->expr()->count('t'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
