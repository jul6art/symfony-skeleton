<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait RepositoryAwareTrait.
 */
trait RepositoryAwareTrait
{
    /**
     * @var string
     */
    protected $sortAsc = 'ASC';

    /**
     * @var string
     */
    protected $sortDesc = 'DESC';

    /**
     * @param QueryBuilder $builder
     * @param string       $field
     * @param string       $value
     *
     * @return self
     */
    public function filterLowercase(QueryBuilder $builder, string $field, string $value): self
    {
        $builder
            ->andWhere($builder->expr()->eq("lower({$field})", ':field'))
            ->setParameter('field', strtolower($value), Type::STRING);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $field
     * @param User         $user
     *
     * @return self
     */
    public function filterByUser(QueryBuilder $builder, string $field, User $user): self
    {
        $builder
            ->andWhere($builder->expr()->eq($field, ':field'))
            ->setParameter('field', $user);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $field
     *
     * @return $this
     */
    public function filterByLatest(QueryBuilder $builder, string $field): self
    {
        $builder
            ->addOrderBy($field, $this->sortDesc)
            ->setMaxResults(1);

        return $this;
    }
}
