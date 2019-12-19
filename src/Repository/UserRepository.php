<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use RepositoryAwareTrait;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param QueryBuilder $builder
     *
     * @return $this
     */
    public function joinGroup(QueryBuilder $builder): self
    {
        if (!\in_array('g', $builder->getAllAliases())) {
            $builder
                ->leftJoin('u.groups', 'g');
        }

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param Group $group
     * @return self
     */
    public function filterByGroup(QueryBuilder $builder, Group $group): self
    {
        $this
            ->joinGroup($builder);

        $builder
            ->andWhere($builder->expr()->eq('g.id', ':group'))
            ->setParameter('group', $group);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param array        $groupList
     *
     * @return $this
     */
    public function filterByGroupList(QueryBuilder $builder, array $groupList): self
    {
        $this
            ->joinGroup($builder);

        $builder
            ->andWhere($builder->expr()->in('g.id', ':groupList'))
            ->setParameter('groupList', $groupList);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $role
     *
     * @return $this
     */
    public function filterByRole(QueryBuilder $builder, string $role): self
    {
        $this
            ->joinGroup($builder);

        $roleName = strtoupper($role);

        $builder
            ->andWhere($builder->expr()->like('g.role', ':role'))
            ->setParameter('role', "'%ROLE_{$roleName}%'");

        return $this;
    }

    /**
     * @param string $name
     *
     * @return User|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByUsername(string $name): ?User
    {
        $builder = $this->createQueryBuilder('u');

        $this
            ->filterLowercase($builder, 'u.username', $name);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $email
     *
     * @return User|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByEmail(string $email): ?User
    {
        $builder = $this->createQueryBuilder('u');

        $this
            ->filterLowercase($builder, 'u.email', $email);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Group $group
     *
     * @return User[]
     */
    public function findByGroup(Group $group): array
    {
        $builder = $this->createQueryBuilder('u');

        $this
            ->filterByGroup($builder, $group);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param array $groupList
     *
     * @return array
     */
    public function findByGroupList(array $groupList): array
    {
        $builder = $this->createQueryBuilder('u');

        $this
            ->filterByGroupList($builder, $groupList);

        return $builder->getQuery()->getResult();
    }

    /**s
     * @param string $role
     *
     * @return User[]
     */
    public function findByRole(string $role): array
    {
        $builder = $this->createQueryBuilder('u');

        $this
            ->filterByRole($builder, $role);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findByUniqueUsername(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findByUniqueEmail(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('u');

        $builder->select($builder->expr()->count('u'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
