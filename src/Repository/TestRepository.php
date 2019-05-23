<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TestRepository
 * @package App\Repository
 */
class TestRepository extends ServiceEntityRepository
{
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
	 * @param QueryBuilder $builder
	 * @param string $name
	 *
	 * @return self
	 */
	public function filterByName(QueryBuilder $builder, string $name): self
	{
		$builder
			->andWhere($builder->expr()->eq('t.name', ':name'))
			->setParameter('name', $name, Type::STRING);

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return Test|null
	 * @throws NonUniqueResultException
	 */
	public function findOneByName(string $name): ?Test
	{
		$builder = $this->createQueryBuilder('t');

		$this
			->filterByName($builder, $name);

		return $builder->getQuery()->getOneOrNullResult();
	}
}
