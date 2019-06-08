<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TestRepository.
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
