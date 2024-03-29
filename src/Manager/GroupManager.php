<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class GroupManager.
 */
class GroupManager extends AbstractManager
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * GroupManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->groupRepository = $this->entityManager->getRepository(Group::class);
    }

    /**
     * @return Group[]
     */
    public function findAllForTable(): array
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->groupRepository->countAll();
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
     * @return Group|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Group
    {
        return $this->groupRepository->findOneByName($name);
    }
}
