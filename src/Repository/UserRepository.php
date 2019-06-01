<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * FunctionalityRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $username
     *
     * @return self
     */
    public function filterByUsername(QueryBuilder $builder, string $username): self
    {
        $builder
            ->andWhere($builder->expr()->eq('u.username', ':username'))
            ->setParameter('username', $username, Type::STRING);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $email
     *
     * @return self
     */
    public function filterByEmail(QueryBuilder $builder, string $email): self
    {
        $builder
            ->andWhere($builder->expr()->eq('u.email', ':email'))
            ->setParameter('email', $email, Type::STRING);

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
            ->filterByUsername($builder, $name);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('u');

        $builder->select('COUNT(u.id)');

        return $builder->getQuery()->getSingleScalarResult();
    }
}
