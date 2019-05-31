<?php

namespace App\Repository;

use App\Entity\Functionality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Functionality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Functionality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Functionality[]    findAll()
 * @method Functionality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunctionalityRepository extends ServiceEntityRepository
{
    /**
     * FunctionalityRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Functionality::class);
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
            ->andWhere($builder->expr()->eq('f.name', ':name'))
            ->setParameter('name', $name, Type::STRING);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Functionality|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Functionality
    {
        $builder = $this->createQueryBuilder('f');

        $this
            ->filterByName($builder, $name);

        return $builder->getQuery()->getOneOrNullResult();
    }
}
