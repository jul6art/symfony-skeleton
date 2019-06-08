<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 06/06/2019
 * Time: 23:13
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait RepositoryTrait
 * @package App\Repository
 */
trait RepositoryTrait {
	/**
	 * @param QueryBuilder $builder
	 * @param string $field
	 * @param string $value
	 *
	 * @return self
	 */
	public function filterLowercase(QueryBuilder $builder, string $field, string $value): self
	{
		$builder
			->andWhere($builder->expr()->eq('lower(' . $field . ')', ':field'))
			->setParameter('field', strtolower($value), Type::STRING);

		return $this;
	}

	/**
	 * @param QueryBuilder $builder
	 * @param string $field
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
}