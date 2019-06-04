<?php

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository
{
	/**
	 * SettingRepository constructor.
	 *
	 * @param RegistryInterface $registry
	 */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setting::class);
    }

	/**
	 * @param QueryBuilder $builder
	 * @param string       $name
	 *
	 * @return self
	 */
	public function filterByName(QueryBuilder $builder, string $name): self
	{
		$builder
			->andWhere($builder->expr()->eq('s.name', ':name'))
			->setParameter('name', $name, Type::STRING);

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return Setting|null
	 *
	 * @throws NonUniqueResultException
	 */
	public function findOneByName(string $name): ?Setting
	{
		$builder = $this->createQueryBuilder('s');

		$this
			->filterByName($builder, $name);

		return $builder->getQuery()->getOneOrNullResult();
	}

	/**
	 * @return int
	 *
	 * @throws NonUniqueResultException
	 */
	public function countAll(): int
	{
		$builder = $this->createQueryBuilder('s');

		$builder->select('COUNT(s.id)');

		return $builder->getQuery()->getSingleScalarResult();
	}
}
