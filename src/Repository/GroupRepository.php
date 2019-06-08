<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
	use RepositoryTrait;

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
     * @param User $user
     *
     * @return Group[]
     */
    public function findByUser(User $user): array
    {
        $builder = $this->createQueryBuilder('g');

        $this
            ->filterByUser($builder, 'g.user', $user);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param string $name
     *
     * @return Group|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Group
    {
        $builder = $this->createQueryBuilder('g');

        $this
            ->filterLowercase($builder, 'g.name', $name);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param User   $user
     * @param string $name
     *
     * @return Group|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByUserAndName(User $user, string $name): ?Group
    {
        $builder = $this->createQueryBuilder('g');

        $this
            ->filterByUser($builder, $user)
            ->filterLowercase($builder, 'g.name', $name);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('g');

        $builder->select($builder->expr()->count('g'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
