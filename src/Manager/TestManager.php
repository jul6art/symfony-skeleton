<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use App\Entity\Test;
use App\Factory\TestFactory;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class TestManager.
 */
class TestManager extends AbstractManager
{
    /**
     * @var TestRepository
     */
    private $testRepository;

    /**
     * GroupManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->testRepository = $this->entityManager->getRepository(Test::class);
    }

    /**
     * @return Test
     */
    public function create(): Test
    {
        return TestFactory::create();
    }

    /**
     * @return Test[]
     */
    public function findAll(): array
    {
        return $this->testRepository->findAll();
    }

    /**
     * @return Test[]
     */
    public function findAllForTable(): array
    {
        return $this->testRepository->findAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->testRepository->countAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAllForTable(): int
    {
        return $this->countAll();
    }

    /**
     * @param string $name
     *
     * @return Test|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Test
    {
        return $this->testRepository->findOneByName($name);
    }
}
