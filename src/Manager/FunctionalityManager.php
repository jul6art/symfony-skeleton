<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use App\Entity\Functionality;
use App\Factory\FunctionalityFactory;
use App\Repository\FunctionalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityManager.
 */
class FunctionalityManager extends AbstractManager
{
    /**
     * @var FunctionalityRepository
     */
    private $functionalityRepository;

    /**
     * @var array
     */
    private $available_functionalities;

    /**
     * FunctionalityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $available_functionalities
     */
    public function __construct(EntityManagerInterface $entityManager, array $available_functionalities)
    {
        parent::__construct($entityManager);
        $this->functionalityRepository = $this->entityManager->getRepository(Functionality::class);
        $this->available_functionalities = $available_functionalities;
    }

    /**
     * @return Functionality
     */
    public function create(): Functionality
    {
        return FunctionalityFactory::create();
    }

    /**
     * @param Functionality $functionality
     * @param bool          $state
     *
     * @return FunctionalityManager
     */
    public function update(Functionality $functionality, bool $state): self
    {
        $functionality->setActive($state);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function isActive(string $name): bool
    {
        $functionality = $this->findOneByName($name);

        if (null !== $functionality) {
            if ($this->isConfigured($functionality)) {
                return $functionality->isActive();
            }
        }

        return false;
    }

    /**
     * @param Functionality $functionality
     *
     * @return bool
     */
    public function isConfigured(Functionality $functionality): bool
    {
        return \in_array($functionality->getName(), $this->available_functionalities);
    }

    /**
     * @return Functionality[]
     */
    public function findAllForTable(): array
    {
        return $this->functionalityRepository->findAll();
    }

    /**
     * @return Functionality[]
     */
    public function findAll(): array
    {
        return $this->functionalityRepository->findAll();
    }

    /**
     * @return Functionality[]
     */
    public function findAllByConfigured(): array
    {
        $functionalities = $this->functionalityRepository->findAll();

        return array_filter($functionalities, function (Functionality $functionality) {
            return $this->isConfigured($functionality);
        });
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->functionalityRepository->countAll();
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
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAllByConfigured(): int
    {
        return \count($this->findAllByConfigured());
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
        return $this->functionalityRepository->findOneByName($name);
    }
}
