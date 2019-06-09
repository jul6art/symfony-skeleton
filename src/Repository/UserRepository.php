<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

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
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        $builder = $this->createQueryBuilder('u');

        $builder->select($builder->expr()->count('u'));

        return $builder->getQuery()->getSingleScalarResult();
    }
}
