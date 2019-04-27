<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GroupRepository
 * @package App\Repository
 */
class GroupRepository extends ServiceEntityRepository
{
	/**
	 * TestRepository constructor.
	 *
	 * @param RegistryInterface $registry
	 */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }

	/**
	 * @param QueryBuilder $builder
	 * @param User $user
	 *
	 * @return self
	 */
    public function filterByUser(QueryBuilder $builder, User $user): self
    {
    	$builder->andWhere(
    		$builder->expr()->eq('g.user', $user)
	    );

    	return $this;
    }

	/**
	 * @param QueryBuilder $builder
	 * @param string $name
	 *
	 * @return self
	 */
    public function filterByName(QueryBuilder $builder, string $name): self
    {
    	$builder->andWhere(
    		$builder->expr()->eq('g.name', $name)
	    );

    	return $this;
    }

	/**
	 * @param User $user
	 *
	 * @return Group[]
	 */
    public function findByUser(User $user): array
    {
    	$builder = $this->createQueryBuilder('g');

    	$this
		    ->filterByUser($builder, $user);

    	return $builder->getQuery()->getResult();
    }

	/**
	 * @param string $name
	 *
	 * @return Group|null
	 * @throws NonUniqueResultException
	 */
    public function findOneByName(string $name): ?Group
    {
    	$builder = $this->createQueryBuilder('g');

    	$this
		    ->filterByName($builder, $name);

    	return $builder->getQuery()->getOneOrNullResult();
    }

	/**
	 * @param User $user
	 * @param string $name
	 *
	 * @return Group|null
	 * @throws NonUniqueResultException
	 */
    public function findOneByUserAndName(User $user, string $name): ?Group
    {
    	$builder = $this->createQueryBuilder('g');

    	$this
		    ->filterByUser($builder, $user)
		    ->filterByName($builder, $name);

    	return $builder->getQuery()->getOneOrNullResult();
    }
}
