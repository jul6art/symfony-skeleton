<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
